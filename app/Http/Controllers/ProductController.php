<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    private function cartSessionKey(): string
    {
        return 'shop_cart';
    }

    private function getCartItems(): array
    {
        return session()->get($this->cartSessionKey(), []);
    }

    private function putCartItems(array $items): void
    {
        session()->put($this->cartSessionKey(), $items);
    }

    private function getCartCount(): int
    {
        return collect($this->getCartItems())->sum('quantity');
    }

    private function ensureTouristAccess(): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isTourist()) {
            abort(403);
        }
    }

    private function ensureShopProductVisible(Product $product): void
    {
        if (!$product->is_approved || !$product->is_available) {
            abort(404);
        }
    }

    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $storeHighlights = collect();
        $activeStore = null;
        $cartCount = $this->getCartCount();
        $cartItems = collect();
        $cartTotal = 0;
        $categoryCounts = collect();
        $selectedCategory = null;
        $selectedSort = 'latest';

        if ($user->isAdmin()) {
            $products = Product::with('vendor')->latest()->paginate(10);
        } elseif ($user->isEnterpriseOwner()) {
            $vendorIds = Vendor::where('user_id', $user->id)->pluck('id');
            $products = Product::with('vendor')->whereIn('vendor_id', $vendorIds)->latest()->paginate(10);
        } else {
            // Tourists browse approved, available products from all vendors.
            $touristProductsQuery = Product::with('vendor')
                ->where('is_approved', true)
                ->where('is_available', true);

            if ($request->filled('vendor')) {
                $activeStore = Vendor::find((int) $request->input('vendor'));
                if ($activeStore) {
                    $touristProductsQuery->where('vendor_id', $activeStore->id);
                }
            }

            if ($request->filled('q')) {
                $keyword = trim((string) $request->input('q', ''));
                $touristProductsQuery->where(function ($query) use ($keyword) {
                    $query->where('name', 'like', "%{$keyword}%")
                        ->orWhere('description', 'like', "%{$keyword}%")
                        ->orWhere('category', 'like', "%{$keyword}%")
                        ->orWhereHas('vendor', function ($vendorQuery) use ($keyword) {
                            $vendorQuery->where('name', 'like', "%{$keyword}%");
                        });
                });
            }

            $categoryCounts = (clone $touristProductsQuery)
                ->selectRaw("COALESCE(NULLIF(TRIM(category), ''), 'Others') as category_label, COUNT(*) as total")
                ->groupBy('category_label')
                ->orderByDesc('total')
                ->get();

            if ($request->filled('category')) {
                $selectedCategory = trim((string) $request->input('category', ''));

                if (strcasecmp($selectedCategory, 'Others') === 0) {
                    $touristProductsQuery->where(function ($query) {
                        $query->whereNull('category')->orWhere('category', '');
                    });
                } else {
                    $touristProductsQuery->where('category', $selectedCategory);
                }
            }

            $selectedSort = (string) $request->input('sort', 'latest');

            switch ($selectedSort) {
                case 'oldest':
                    $touristProductsQuery->oldest();
                    break;
                case 'price_asc':
                    $touristProductsQuery->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $touristProductsQuery->orderBy('price', 'desc');
                    break;
                case 'name_asc':
                    $touristProductsQuery->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $touristProductsQuery->orderBy('name', 'desc');
                    break;
                default:
                    $selectedSort = 'latest';
                    $touristProductsQuery->latest();
                    break;
            }

            $products = $touristProductsQuery->paginate(10);
            $products->appends($request->query());

            $storeHighlights = Vendor::query()
                ->whereHas('products', function ($query) {
                    $query->where('is_approved', true)->where('is_available', true);
                })
                ->withCount(['products as active_products_count' => function ($query) {
                    $query->where('is_approved', true)->where('is_available', true);
                }])
                ->orderByDesc('active_products_count')
                ->take(8)
                ->get();

            $sessionItems = $this->getCartItems();
            $cartProductIds = collect($sessionItems)->pluck('product_id')->values()->all();
            $cartProducts = Product::with('vendor')->whereIn('id', $cartProductIds)->get()->keyBy('id');

            $cartItems = collect($sessionItems)->map(function ($item) use ($cartProducts) {
                $product = $cartProducts->get($item['product_id']);

                if (!$product || !$product->is_approved || !$product->is_available) {
                    return null;
                }

                $quantity = max(1, (int) $item['quantity']);
                if (!is_null($product->stock) && $product->stock > 0) {
                    $quantity = min($quantity, $product->stock);
                }

                return [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $quantity * (float) $product->price,
                ];
            })->filter()->values();

            $normalized = $cartItems->mapWithKeys(function ($item) {
                return [
                    $item['product']->id => [
                        'product_id' => $item['product']->id,
                        'quantity' => $item['quantity'],
                    ],
                ];
            })->all();

            $this->putCartItems($normalized);
            $cartCount = $this->getCartCount();
            $cartTotal = $cartItems->sum('subtotal');
        }

        return view('dashboard.products.index', compact(
            'products',
            'storeHighlights',
            'activeStore',
            'cartCount',
            'cartItems',
            'cartTotal',
            'categoryCounts',
            'selectedCategory',
            'selectedSort'
        ));
    }

    public function show(Product $product)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isTourist()) {
            $this->ensureShopProductVisible($product);
        }

        $product->load('vendor');
        $cartCount = $this->getCartCount();

        return view('dashboard.products.show', compact('product', 'cartCount'));
    }

    public function cart()
    {
        $this->ensureTouristAccess();

        $sessionItems = $this->getCartItems();
        $productIds = collect($sessionItems)->pluck('product_id')->values()->all();

        $products = Product::with('vendor')->whereIn('id', $productIds)->get()->keyBy('id');

        $items = collect($sessionItems)->map(function ($item) use ($products) {
            $product = $products->get($item['product_id']);

            if (!$product || !$product->is_approved || !$product->is_available) {
                return null;
            }

            $quantity = max(1, (int) $item['quantity']);
            if (!is_null($product->stock) && $product->stock > 0) {
                $quantity = min($quantity, $product->stock);
            }

            return [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $quantity * (float) $product->price,
            ];
        })->filter()->values();

        $normalized = $items->mapWithKeys(function ($item) {
            return [
                $item['product']->id => [
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                ],
            ];
        })->all();

        $this->putCartItems($normalized);

        $total = $items->sum('subtotal');
        $cartCount = $this->getCartCount();

        return view('dashboard.products.cart', compact('items', 'total', 'cartCount'));
    }

    public function addToCart(Request $request, Product $product)
    {
        $this->ensureTouristAccess();
        $this->ensureShopProductVisible($product);

        $validated = $request->validate([
            'quantity' => 'nullable|integer|min:1',
        ]);

        $quantityToAdd = (int) ($validated['quantity'] ?? 1);
        $items = $this->getCartItems();
        $existingQty = (int) data_get($items, $product->id . '.quantity', 0);
        $newQty = $existingQty + $quantityToAdd;

        if (!is_null($product->stock) && $product->stock > 0) {
            $newQty = min($newQty, $product->stock);
        }

        $items[$product->id] = [
            'product_id' => $product->id,
            'quantity' => max(1, $newQty),
        ];

        $this->putCartItems($items);

        return redirect()->back()->with('success', "'{$product->name}' added to cart.");
    }

    public function updateCart(Request $request, Product $product)
    {
        $this->ensureTouristAccess();

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $items = $this->getCartItems();

        if (!isset($items[$product->id])) {
            return redirect()->route('products.cart')->with('error', 'Item not found in cart.');
        }

        $quantity = (int) $validated['quantity'];
        if (!is_null($product->stock) && $product->stock > 0) {
            $quantity = min($quantity, $product->stock);
        }

        $items[$product->id]['quantity'] = max(1, $quantity);
        $this->putCartItems($items);

        return redirect()->route('products.cart');
    }

    public function removeFromCart(Product $product)
    {
        $this->ensureTouristAccess();

        $items = $this->getCartItems();
        unset($items[$product->id]);
        $this->putCartItems($items);

        return redirect()->route('products.cart')->with('success', 'Item removed from cart.');
    }

    public function create()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isAdmin()) {
            $vendors = Vendor::all();
        } else {
            $vendors = Vendor::where('user_id', $user->id)->get();
        }

        return view('dashboard.products.create', compact('vendors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:255',
            'stock' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'is_available' => 'boolean',
        ]);

        $validated['is_available'] = $request->has('is_available');

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Auto-approve if created by admin
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->isAdmin()) {
            $validated['is_approved'] = true;
            $validated['approved_at'] = now();
        } else {
            $validated['is_approved'] = false;
        }

        Product::create($validated);

        $message = $user->isAdmin() 
            ? 'Product created successfully!' 
            : 'Product submitted for approval. Admin will review your listing.';

        return redirect()->route('products.index')->with('success', $message);
    }

    public function edit(Product $product)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isAdmin()) {
            $vendors = Vendor::all();
        } else {
            $vendors = Vendor::where('user_id', $user->id)->get();
        }

        return view('dashboard.products.edit', compact('product', 'vendors'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:255',
            'stock' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'is_available' => 'boolean',
        ]);

        $validated['is_available'] = $request->has('is_available');

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }

    public function approve(Product $product)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            abort(403);
        }

        $product->is_approved = true;
        $product->approved_at = now();
        $product->rejection_reason = null;
        $product->save();

        return redirect()->back()->with('success', "Product '{$product->name}' has been approved!");
    }

    public function reject(Request $request, Product $product)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $product->is_approved = false;
        $product->rejection_reason = $request->rejection_reason ?? 'Product listing does not meet our guidelines.';
        $product->save();

        return redirect()->back()->with('success', "Product '{$product->name}' has been rejected.");
    }
}

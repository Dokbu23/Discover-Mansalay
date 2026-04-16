<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $products = Product::with('vendor')->latest()->paginate(10);
        } else {
            $vendorIds = Vendor::where('user_id', $user->id)->pluck('id');
            $products = Product::whereIn('vendor_id', $vendorIds)->latest()->paginate(10);
        }

        return view('dashboard.products.index', compact('products'));
    }

    public function create()
    {
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

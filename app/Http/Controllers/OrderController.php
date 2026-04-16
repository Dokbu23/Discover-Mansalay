<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Order::with(['user', 'product', 'vendor']);

        if ($user->isAdmin()) {
            // Admin sees all orders
        } elseif ($user->isEnterpriseOwner()) {
            // Enterprise owner sees orders for their vendor's products
            $vendorIds = Vendor::where('user_id', $user->id)->pluck('id');
            $query->whereIn('vendor_id', $vendorIds);
        } else {
            // Tourist sees their own orders
            $query->where('user_id', $user->id);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(15);

        return view('dashboard.orders.index', compact('orders'));
    }

    public function create()
    {
        $user = Auth::user();
        
        // Only tourists can create orders from the dashboard
        $products = Product::where('is_approved', true)
            ->where('is_available', true)
            ->with('vendor')
            ->get();

        return view('dashboard.orders.create', compact('products'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'shipping_address' => 'required|string',
            'contact_number' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        Order::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'vendor_id' => $product->vendor_id,
            'quantity' => $validated['quantity'],
            'total_price' => $product->price * $validated['quantity'],
            'shipping_address' => $validated['shipping_address'],
            'contact_number' => $validated['contact_number'],
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('orders.index')->with('success', 'Order placed successfully!');
    }

    public function show(Order $order)
    {
        $user = Auth::user();

        // Check authorization
        if (!$user->isAdmin()) {
            $vendorIds = Vendor::where('user_id', $user->id)->pluck('id')->toArray();
            if ($order->user_id !== $user->id && !in_array($order->vendor_id, $vendorIds)) {
                abort(403);
            }
        }

        $order->load(['user', 'product', 'vendor']);

        return view('dashboard.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $user = Auth::user();

        // Only admin or vendor owner can update status
        if (!$user->isAdmin()) {
            $vendorIds = Vendor::where('user_id', $user->id)->pluck('id')->toArray();
            if (!in_array($order->vendor_id, $vendorIds)) {
                abort(403);
            }
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
        ]);

        $order->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Order status updated!');
    }

    public function destroy(Order $order)
    {
        $user = Auth::user();

        // Only admin can delete, or user can cancel their own pending order
        if (!$user->isAdmin()) {
            if ($order->user_id !== $user->id || $order->status !== 'pending') {
                abort(403);
            }
        }

        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Order deleted!');
    }
}

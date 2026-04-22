<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class AwatiBuyerDashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $orderQuery = Order::with(['product', 'vendor'])
            ->where('user_id', $user->id);

        $totalOrders = (clone $orderQuery)->count();
        $pendingOrders = (clone $orderQuery)->whereIn('status', ['pending', 'confirmed', 'processing'])->count();
        $deliveredOrders = (clone $orderQuery)->where('status', 'delivered')->count();
        $totalSpent = (clone $orderQuery)
            ->where('status', '!=', 'cancelled')
            ->sum('total_price');

        $recentOrders = (clone $orderQuery)
            ->latest()
            ->take(6)
            ->get();

        $recommendedProducts = Product::with('vendor')
            ->where('is_available', true)
            ->where('is_approved', true)
            ->latest()
            ->take(8)
            ->get();

        return view('awati.buyer-dashboard', [
            'totalOrders' => $totalOrders,
            'pendingOrders' => $pendingOrders,
            'deliveredOrders' => $deliveredOrders,
            'totalSpent' => $totalSpent,
            'recentOrders' => $recentOrders,
            'recommendedProducts' => $recommendedProducts,
        ]);
    }
}

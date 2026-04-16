@extends('layouts.dashboard')

@section('title', 'Orders')
@section('page-title', 'Orders')

@section('header-actions')
@if(Auth::user()->isTourist())
<a href="{{ route('orders.create') }}" class="btn btn-primary">
    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>
    New Order
</a>
@endif
@endsection

@section('content')
<!-- Filters -->
<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-body">
        <form method="GET" action="{{ route('orders.index') }}" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: end;">
            <div class="form-group" style="margin: 0; min-width: 150px;">
                <label class="form-label">Status</label>
                <select name="status" class="form-input">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">Reset</a>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>All Orders</h3>
        <span style="color: #7a4d63; font-size: 0.9rem;">{{ $orders->total() }} orders found</span>
    </div>
    <div class="card-body" style="padding: 0;">
        @if($orders->count() > 0)
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Product</th>
                        @if(Auth::user()->isAdmin() || Auth::user()->isEnterpriseOwner())
                        <th>Customer</th>
                        @endif
                        <th>Qty</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td><strong>#{{ $order->id }}</strong></td>
                        <td>{{ $order->product->name ?? 'N/A' }}</td>
                        @if(Auth::user()->isAdmin() || Auth::user()->isEnterpriseOwner())
                        <td>{{ $order->user->name ?? 'N/A' }}</td>
                        @endif
                        <td>{{ $order->quantity }}</td>
                        <td>₱{{ number_format($order->total_price, 2) }}</td>
                        <td>
                            @php
                                $statusColors = [
                                    'pending' => 'background: #fef3c7; color: #92400e;',
                                    'confirmed' => 'background: #dbeafe; color: #1e40af;',
                                    'processing' => 'background: #e0e7ff; color: #3730a3;',
                                    'shipped' => 'background: #fce7f3; color: #9d174d;',
                                    'delivered' => 'background: #ffe3f1; color: #166534;',
                                    'cancelled' => 'background: #fee2e2; color: #991b1b;',
                                ];
                            @endphp
                            <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 500; {{ $statusColors[$order->status] ?? '' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td style="font-size: 0.85rem; color: #6b7280;">{{ $order->created_at->format('M d, Y') }}</td>
                        <td>
                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary btn-sm">View</a>
                                @if((Auth::user()->isAdmin() || Auth::user()->isEnterpriseOwner()) && $order->status !== 'delivered' && $order->status !== 'cancelled')
                                <form action="{{ route('orders.updateStatus', $order) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="form-input" style="padding: 0.3rem; font-size: 0.75rem;">
                                        <option value="">Update...</option>
                                        <option value="confirmed">Confirm</option>
                                        <option value="processing">Processing</option>
                                        <option value="shipped">Shipped</option>
                                        <option value="delivered">Delivered</option>
                                        <option value="cancelled">Cancel</option>
                                    </select>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding: 1rem;">{{ $orders->withQueryString()->links() }}</div>
        @else
        <div class="empty-state" style="padding: 3rem; text-align: center;">
            <svg width="48" height="48" fill="none" stroke="#9ca3af" viewBox="0 0 24 24" style="margin: 0 auto 1rem;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            <p style="color: #6b7280;">No orders found</p>
        </div>
        @endif
    </div>
</div>
@endsection


@extends('layouts.dashboard')

@section('title', 'Orders')
@section('page-title', Auth::user()->isTourist() ? '' : 'Orders')

@section('content')
@if(Auth::user()->isTourist())
@php
    $statusMap = [
        '' => 'All',
        'pending' => 'To Pay',
        'confirmed' => 'Confirmed',
        'processing' => 'To Ship',
        'shipped' => 'To Receive',
        'delivered' => 'Completed',
        'cancelled' => 'Cancelled',
    ];

    $statusColors = [
        'pending' => 'status-pending',
        'confirmed' => 'status-confirmed',
        'processing' => 'status-processing',
        'shipped' => 'status-shipped',
        'delivered' => 'status-delivered',
        'cancelled' => 'status-cancelled',
    ];
@endphp

<section class="orders-hero">
    <div>
        <h2>My Orders</h2>
        <p>Track your purchases, shipping status, and completed transactions.</p>
    </div>
    <a href="{{ route('orders.create') }}" class="btn btn-primary">Order Again</a>
</section>

<div class="order-status-tabs">
    @foreach($statusMap as $value => $label)
        <a href="{{ route('orders.index', array_filter(['status' => $value])) }}" class="order-status-tab {{ request('status', '') === $value ? 'active' : '' }}">{{ $label }}</a>
    @endforeach
</div>

@if($orders->count() > 0)
    <div class="orders-card-list">
        @foreach($orders as $order)
            <article class="order-card">
                <div class="order-card-head">
                    <div>
                        <strong>Order #{{ $order->id }}</strong>
                        <span>{{ $order->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                    <span class="order-status {{ $statusColors[$order->status] ?? '' }}">{{ ucfirst($order->status) }}</span>
                </div>

                <div class="order-card-body">
                    <div class="order-product-media">
                        @if(optional($order->product)->image)
                            <img src="{{ asset('storage/' . $order->product->image) }}" alt="{{ $order->product->name }}">
                        @else
                            <div class="order-media-placeholder">No Image</div>
                        @endif
                    </div>

                    <div class="order-product-info">
                        <h4>{{ optional($order->product)->name ?? 'Product unavailable' }}</h4>
                        <p>Store: {{ optional($order->vendor)->name ?? 'Local Store' }}</p>
                        <div class="order-metrics">
                            <span>Qty: {{ $order->quantity }}</span>
                            <span>Total: ₱{{ number_format($order->total_price, 2) }}</span>
                        </div>
                    </div>

                    <div class="order-actions">
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary btn-sm">Order Details</a>
                        @if($order->product_id)
                            <a href="{{ route('orders.create', ['product_id' => $order->product_id]) }}" class="btn btn-primary btn-sm">Buy Again</a>
                        @endif
                    </div>
                </div>
            </article>
        @endforeach
    </div>
    <div style="padding-top: 1rem;">{{ $orders->withQueryString()->links() }}</div>
@else
    <div class="empty-state" style="padding: 3rem; text-align: center;">
        <svg width="48" height="48" fill="none" stroke="#9ca3af" viewBox="0 0 24 24" style="margin: 0 auto 1rem;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
        </svg>
        <p style="color: #6b7280;">No orders found for this status.</p>
    </div>
@endif
@else
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
                                <span class="table-status-badge table-status-{{ $order->status }}">
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
@endif
@endsection

@section('styles')
.table-status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
}

.table-status-pending { background: #fef3c7; color: #92400e; }
.table-status-confirmed { background: #dbeafe; color: #1e40af; }
.table-status-processing { background: #e0e7ff; color: #3730a3; }
.table-status-shipped { background: #fce7f3; color: #9d174d; }
.table-status-delivered { background: #dcfce7; color: #166534; }
.table-status-cancelled { background: #fee2e2; color: #991b1b; }

@if(Auth::user()->isTourist())
.orders-hero {
    background: linear-gradient(180deg, #db2777 0%, #ec4899 100%);
    border-radius: 10px;
    padding: 0.8rem 0.95rem;
    margin-bottom: 0.7rem;
    color: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
}

.orders-hero h2 {
    margin: 0;
    font-size: 1.2rem;
    color: #fff;
}

.orders-hero p {
    margin-top: 0.25rem;
    opacity: 0.92;
    font-size: 0.8rem;
}

.orders-hero .btn-primary {
    background: #fff;
    color: #be185d;
}

.orders-hero .btn-primary:hover {
    background: #ffe4f1;
}

.order-status-tabs {
    background: #fff;
    border: 1px solid #f4c5dc;
    border-radius: 10px;
    padding: 0.75rem 1.5rem;
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 1rem;
    justify-content: space-around;
    align-items: center;
}

.order-status-tab {
    text-decoration: none;
    color: #9d174d;
    font-size: 0.78rem;
    font-weight: 20;
    padding: 0.5rem 1.2rem;
    border-radius: 10px;
    border: none;
    background: #fce4f1;
    white-space: nowrap;
    transition: all 0.2s ease;
}

.order-status-tab.active {
    background: #db2777;
    color: #fff;
    border: none;
}

.orders-card-list {
    display: grid;
    gap: 0.55rem;
}

.order-card {
    background: #fff;
    border: 1px solid #f1d5e4;
    border-radius: 10px;
    overflow: hidden;
}

.order-card-head {
    padding: 0.4rem 0.75rem;
    border-bottom: 1px solid #f8e6ef;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 0.6rem;
}

.order-card-head strong {
    display: block;
    color: #3b1f2e;
    font-size: 0.86rem;
}

.order-card-head span {
    font-size: 0.72rem;
    color: #7a4d63;
}

.order-card-body {
    padding: 0.5rem 0.75rem;
    display: grid;
    grid-template-columns: 64px 1fr auto;
    gap: 0.6rem;
    align-items: center;
}

.order-product-media {
    width: 64px;
    height: 64px;
    border-radius: 8px;
    overflow: hidden;
    background: #fff7fb;
}

.order-product-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.order-media-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #a16285;
    font-size: 0.7rem;
}

.order-product-info h4 {
    color: #3b1f2e;
    margin-bottom: 0.15rem;
    font-size: 0.9rem;
}

.order-product-info p {
    color: #7a4d63;
    font-size: 0.74rem;
}

.order-metrics {
    display: flex;
    gap: 0.35rem;
    margin-top: 0.35rem;
    flex-wrap: wrap;
}

.order-metrics span {
    background: #fff1f7;
    border-radius: 999px;
    color: #9d174d;
    font-size: 0.69rem;
    padding: 0.2rem 0.48rem;
}

.order-actions {
    display: flex;
    gap: 0.35rem;
    flex-wrap: wrap;
    justify-content: flex-end;
}

.order-actions .btn {
    padding: 0.34rem 0.62rem;
    font-size: 0.74rem;
}

.order-status {
    font-size: 0.68rem;
    font-weight: 600;
    border-radius: 999px;
    padding: 0.2rem 0.52rem;
}

.status-pending { background: #fef3c7; color: #92400e; }
.status-confirmed { background: #dbeafe; color: #1e40af; }
.status-processing { background: #e0e7ff; color: #3730a3; }
.status-shipped { background: #fce7f3; color: #9d174d; }
.status-delivered { background: #dcfce7; color: #166534; }
.status-cancelled { background: #fee2e2; color: #991b1b; }

@media (max-width: 900px) {
    .orders-hero {
        flex-direction: column;
        align-items: flex-start;
    }

    .order-card-body {
        grid-template-columns: 1fr;
    }

    .order-actions {
        justify-content: flex-start;
    }
}
@endif
@endsection


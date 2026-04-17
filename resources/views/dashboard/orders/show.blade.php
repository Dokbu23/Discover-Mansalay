@extends('layouts.dashboard')

@section('title', 'Order #' . $order->id)
@section('page-title', Auth::user()->isTourist() ? '' : 'Order Details')

@section('header-actions')
<a href="{{ route('orders.index') }}" class="btn btn-secondary">
    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
    </svg>
    Back to Orders
</a>
@endsection

@section('content')
@php
    $statusClassMap = [
        'pending' => 'order-status-pending',
        'confirmed' => 'order-status-confirmed',
        'processing' => 'order-status-processing',
        'shipped' => 'order-status-shipped',
        'delivered' => 'order-status-delivered',
        'cancelled' => 'order-status-cancelled',
    ];
@endphp

<section class="order-view-hero">
    <div>
        <h2>Order #{{ $order->id }}</h2>
        <p>Placed on {{ $order->created_at->format('M d, Y h:i A') }}</p>
    </div>
    <span class="order-status-pill {{ $statusClassMap[$order->status] ?? '' }}">{{ ucfirst($order->status) }}</span>
</section>

<div class="order-view-grid">
    <div class="order-main-stack">
        <article class="order-panel">
            <h3>Product</h3>
            @if($order->product)
                <div class="order-product-row">
                    <div class="order-product-media">
                        @if($order->product->image)
                            <img src="{{ asset('storage/' . $order->product->image) }}" alt="{{ $order->product->name }}">
                        @else
                            <div class="order-product-placeholder">No Image</div>
                        @endif
                    </div>
                    <div class="order-product-copy">
                        <h4>{{ $order->product->name }}</h4>
                        <p>₱{{ number_format($order->product->price, 2) }} each</p>
                        @if($order->product->description)
                            <p class="muted">{{ \Illuminate\Support\Str::limit($order->product->description, 130) }}</p>
                        @endif
                    </div>
                </div>
            @else
                <p class="muted">Product information unavailable.</p>
            @endif
        </article>

        <article class="order-panel">
            <h3>Order Summary</h3>
            <div class="order-summary-rows">
                <div><span>Order Date</span><strong>{{ $order->created_at->format('M d, Y h:i A') }}</strong></div>
                <div><span>Last Updated</span><strong>{{ $order->updated_at->format('M d, Y h:i A') }}</strong></div>
                <div><span>Quantity</span><strong>{{ $order->quantity }}</strong></div>
                <div><span>Total</span><strong class="amount">₱{{ number_format($order->total_price, 2) }}</strong></div>
            </div>
            @if($order->notes)
                <div class="order-notes">
                    <strong>Order Notes</strong>
                    <p>{{ $order->notes }}</p>
                </div>
            @endif
        </article>
    </div>

    <aside class="order-side-stack">
        <article class="order-panel">
            <h3>Customer Information</h3>
            <div class="info-rows">
                <div><span>Name</span><strong>{{ $order->user->name ?? 'N/A' }}</strong></div>
                <div><span>Email</span><strong>{{ $order->user->email ?? 'N/A' }}</strong></div>
                <div><span>Contact #</span><strong>{{ $order->contact_number ?? 'Not provided' }}</strong></div>
                <div><span>Shipping Address</span><strong>{{ $order->shipping_address ?? 'Not provided' }}</strong></div>
            </div>
        </article>

        <article class="order-panel">
            <h3>Vendor Information</h3>
            @if($order->vendor)
                <div class="info-rows">
                    <div><span>Business Name</span><strong>{{ $order->vendor->name }}</strong></div>
                    <div><span>Owner</span><strong>{{ $order->vendor->user->name ?? 'N/A' }}</strong></div>
                    <div><span>Contact</span><strong>{{ $order->vendor->contact_number ?? 'N/A' }}</strong></div>
                </div>
            @else
                <p class="muted">Vendor information unavailable.</p>
            @endif
        </article>
    </aside>
</div>

<!-- Actions -->
@if((Auth::user()->isAdmin() || Auth::user()->isEnterpriseOwner()) && $order->status !== 'delivered' && $order->status !== 'cancelled')
<div class="card order-action-card" style="margin-top: 1rem;">
    <div class="card-header">
        <h3>Update Order Status</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('orders.updateStatus', $order) }}" method="POST" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: end;">
            @csrf
            @method('PATCH')
            <div class="form-group" style="margin: 0; min-width: 200px;">
                <label class="form-label">New Status</label>
                <select name="status" class="form-input" required>
                    <option value="">Select status...</option>
                    @if($order->status === 'pending')
                    <option value="confirmed">Confirm Order</option>
                    <option value="cancelled">Cancel Order</option>
                    @elseif($order->status === 'confirmed')
                    <option value="processing">Start Processing</option>
                    <option value="cancelled">Cancel Order</option>
                    @elseif($order->status === 'processing')
                    <option value="shipped">Mark as Shipped</option>
                    <option value="cancelled">Cancel Order</option>
                    @elseif($order->status === 'shipped')
                    <option value="delivered">Mark as Delivered</option>
                    @endif
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Status</button>
        </form>
    </div>
</div>
@endif

@if(Auth::user()->isTourist() && $order->user_id === Auth::id() && $order->status === 'pending')
<div class="card order-action-card" style="margin-top: 1rem;">
    <div class="card-header">
        <h3>Cancel Order</h3>
    </div>
    <div class="card-body">
        <p style="color: #6b7280; margin-bottom: 1rem;">You can cancel this order while it's still pending.</p>
        <form action="{{ route('orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Cancel Order</button>
        </form>
    </div>
</div>
@endif
@endsection

@section('styles')
.order-view-hero {
    background: linear-gradient(180deg, #db2777 0%, #ec4899 100%);
    color: #fff;
    border-radius: 14px;
    padding: 0.95rem 1.1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.order-view-hero h2 {
    color: #fff;
    font-size: 1.25rem;
}

.order-view-hero p {
    margin-top: 0.25rem;
    font-size: 0.82rem;
    opacity: 0.9;
}

.order-status-pill {
    padding: 0.33rem 0.72rem;
    border-radius: 999px;
    font-size: 0.74rem;
    font-weight: 600;
}

.order-status-pending { background: #fef3c7; color: #92400e; }
.order-status-confirmed { background: #dbeafe; color: #1e40af; }
.order-status-processing { background: #e0e7ff; color: #3730a3; }
.order-status-shipped { background: #fce7f3; color: #9d174d; }
.order-status-delivered { background: #dcfce7; color: #166534; }
.order-status-cancelled { background: #fee2e2; color: #991b1b; }

.order-view-grid {
    display: grid;
    grid-template-columns: 1.35fr 1fr;
    gap: 1rem;
}

.order-main-stack,
.order-side-stack {
    display: grid;
    gap: 1rem;
}

.order-panel {
    background: #fff;
    border: 1px solid #f4c5dc;
    border-radius: 12px;
    padding: 1rem;
}

.order-panel h3 {
    color: #be185d;
    margin-bottom: 0.8rem;
    font-size: 1rem;
}

.order-product-row {
    display: flex;
    gap: 0.85rem;
    align-items: flex-start;
}

.order-product-media {
    width: 90px;
    height: 90px;
    border-radius: 10px;
    overflow: hidden;
    background: #fff7fb;
    flex-shrink: 0;
}

.order-product-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.order-product-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.72rem;
    color: #a16285;
}

.order-product-copy h4 {
    font-size: 1.02rem;
    color: #3b1f2e;
}

.order-product-copy p {
    color: #7a4d63;
    margin-top: 0.22rem;
}

.muted {
    color: #7a4d63;
    font-size: 0.85rem;
}

.order-summary-rows,
.info-rows {
    display: grid;
    gap: 0.6rem;
}

.order-summary-rows > div,
.info-rows > div {
    display: grid;
    grid-template-columns: 120px 1fr;
    gap: 0.55rem;
    padding-bottom: 0.6rem;
    border-bottom: 1px solid #f2e8ef;
}

.order-summary-rows > div:last-child,
.info-rows > div:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.order-summary-rows span,
.info-rows span {
    color: #7a4d63;
    font-size: 0.84rem;
}

.order-summary-rows strong,
.info-rows strong {
    color: #3b1f2e;
    font-size: 0.9rem;
}

.order-summary-rows .amount {
    color: #be185d;
    font-size: 1.1rem;
}

.order-notes {
    margin-top: 0.85rem;
    border-radius: 10px;
    background: #fff1f7;
    padding: 0.75rem;
}

.order-notes strong {
    color: #3b1f2e;
}

.order-notes p {
    color: #7a4d63;
    margin-top: 0.3rem;
}

.order-action-card {
    border: 1px solid #f4c5dc;
}

@media (max-width: 980px) {
    .order-view-hero {
        flex-direction: column;
        align-items: flex-start;
    }

    .order-view-grid {
        grid-template-columns: 1fr;
    }

    .order-summary-rows > div,
    .info-rows > div {
        grid-template-columns: 1fr;
        gap: 0.2rem;
    }
}
@endsection



@extends('layouts.dashboard')

@section('title', 'My Cart')
@section('page-title', '')

@section('header-actions')
<a href="{{ route('products.index') }}" class="btn btn-secondary">Continue Shopping</a>
@endsection

@section('content')
<section class="cart-hero">
    <div>
        <h2>My Cart</h2>
        <p>Review your selected items before checkout.</p>
    </div>
    <a href="{{ route('orders.index') }}" class="btn btn-secondary">My Orders</a>
</section>

@if($items->count() > 0)
    @php
        $firstItem = $items->first();
        $firstProductId = data_get($firstItem, 'product.id');
    @endphp

    <div class="cart-shell">
        <div class="cart-list">
            @foreach($items as $item)
                @php
                    $product = $item['product'];
                    $currentQty = (int) $item['quantity'];
                    $maxQty = ($product->stock && $product->stock > 0) ? (int) $product->stock : 9999;
                @endphp
                <article class="cart-item">
                    <div class="cart-item-main">
                        <div class="cart-item-media">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                            @else
                                <div class="cart-item-placeholder">No Image</div>
                            @endif
                        </div>

                        <div class="cart-item-copy">
                            <a href="{{ route('products.show', $product) }}" class="cart-item-name">{{ $product->name }}</a>
                            <p class="cart-item-store">Store: {{ optional($product->vendor)->name ?? 'Local Store' }}</p>
                            <p class="cart-item-price">₱{{ number_format($product->price, 2) }}</p>
                        </div>
                    </div>

                    <div class="cart-item-actions">
                        <div class="cart-qty-stepper">
                            <form action="{{ route('products.cart.update', $product) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="quantity" value="{{ max(1, $currentQty - 1) }}">
                                <button type="submit" class="cart-step-btn" {{ $currentQty <= 1 ? 'disabled' : '' }}>-</button>
                            </form>

                            <span>{{ $currentQty }}</span>

                            <form action="{{ route('products.cart.update', $product) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="quantity" value="{{ min($maxQty, $currentQty + 1) }}">
                                <button type="submit" class="cart-step-btn" {{ $currentQty >= $maxQty ? 'disabled' : '' }}>+</button>
                            </form>
                        </div>

                        <div class="cart-subtotal">Subtotal: ₱{{ number_format($item['subtotal'], 2) }}</div>

                        <div class="cart-item-buttons">
                            <a href="{{ route('orders.create', ['product_id' => $product->id]) }}" class="btn btn-primary btn-sm">Checkout</a>
                            <form action="{{ route('products.cart.remove', $product) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-secondary btn-sm">Remove</button>
                            </form>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <aside class="cart-summary">
            <h4>Order Summary</h4>
            <div class="cart-summary-row">
                <span>Items</span>
                <strong>{{ $cartCount }}</strong>
            </div>
            <div class="cart-summary-row">
                <span>Subtotal</span>
                <strong>₱{{ number_format($total, 2) }}</strong>
            </div>
            <div class="cart-summary-row cart-summary-total">
                <span>Total</span>
                <strong>₱{{ number_format($total, 2) }}</strong>
            </div>

            @if($firstProductId)
                <a href="{{ route('orders.create', ['product_id' => $firstProductId]) }}" class="btn btn-primary" style="width: 100%; justify-content: center;">Proceed to Checkout</a>
            @endif
            <p class="cart-summary-note">Checkout works per product, similar to ordering one item at a time.</p>
        </aside>
    </div>
@else
    <div class="empty-state">
        <p>Your cart is empty.</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary" style="margin-top: 1rem;">Browse Products</a>
    </div>
@endif
@endsection

@section('styles')
.cart-hero {
    background: linear-gradient(180deg, #db2777 0%, #ec4899 100%);
    border-radius: 12px;
    padding: 0.9rem 1rem;
    margin-bottom: 0.9rem;
    color: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
}

.cart-hero h2 {
    font-size: 1.18rem;
    color: #fff;
}

.cart-hero p {
    margin-top: 0.2rem;
    font-size: 0.82rem;
    opacity: 0.95;
}

.cart-shell {
    display: grid;
    grid-template-columns: 1fr 280px;
    gap: 1rem;
}

.cart-list {
    display: grid;
    gap: 0.85rem;
}

.cart-item {
    border: 1px solid #f1d5e4;
    border-radius: 12px;
    padding: 0.75rem;
    display: grid;
    grid-template-columns: 1fr 230px;
    gap: 0.8rem;
}

.cart-item-main {
    display: flex;
    gap: 0.8rem;
    align-items: center;
}

.cart-item-media {
    width: 70px;
    height: 70px;
    border-radius: 8px;
    overflow: hidden;
    background: #fff7fb;
}

.cart-item-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.cart-item-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    color: #a16285;
}

.cart-item-name {
    color: #3b1f2e;
    font-weight: 600;
    text-decoration: none;
}

.cart-item-store {
    color: #7a4d63;
    font-size: 0.82rem;
}

.cart-item-price {
    color: #be185d;
    font-weight: 700;
    margin-top: 0.2rem;
}

.cart-item-actions {
    display: grid;
    gap: 0.45rem;
    justify-items: start;
    align-content: center;
}

.cart-qty-stepper {
    display: flex;
    align-items: center;
    gap: 0.3rem;
    border: 1px solid #f4c5dc;
    border-radius: 8px;
    padding: 0.25rem 0.35rem;
    background: #fff;
}

.cart-qty-stepper span {
    min-width: 28px;
    text-align: center;
    font-weight: 600;
    color: #3b1f2e;
}

.cart-step-btn {
    width: 26px;
    height: 26px;
    border-radius: 6px;
    border: 1px solid #f4c5dc;
    background: #fff1f7;
    color: #9d174d;
    cursor: pointer;
    font-weight: 700;
}

.cart-step-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.cart-subtotal {
    font-size: 0.85rem;
    color: #7a4d63;
}

.cart-item-buttons {
    display: flex;
    gap: 0.45rem;
}

.cart-summary {
    border: 1px solid #f1d5e4;
    border-radius: 12px;
    padding: 0.85rem;
    background: #fff;
    height: fit-content;
    position: sticky;
    top: 90px;
}

.cart-summary h4 {
    color: #be185d;
    margin-bottom: 0.65rem;
}

.cart-summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.55rem;
    color: #7a4d63;
}

.cart-summary-total {
    border-top: 1px solid #fde7f3;
    margin-top: 0.4rem;
    padding-top: 0.55rem;
}

.cart-summary-total strong {
    color: #be185d;
    font-size: 1.05rem;
}

.cart-summary-note {
    margin-top: 0.65rem;
    font-size: 0.76rem;
    color: #7a4d63;
}

@media (max-width: 900px) {
    .cart-hero {
        flex-direction: column;
        align-items: flex-start;
    }

    .cart-shell {
        grid-template-columns: 1fr;
    }

    .cart-item {
        grid-template-columns: 1fr;
    }

    .cart-item-actions {
        justify-items: start;
    }

    .cart-summary {
        position: static;
    }
}
@endsection

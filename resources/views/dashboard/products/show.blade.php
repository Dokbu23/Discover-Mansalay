@extends('layouts.dashboard')

@section('title', 'Product Details')
@section('page-title', Auth::user()->isTourist() ? '' : 'Product Details')

@section('header-actions')
@if(Auth::user()->isTourist())
    <a href="{{ route('products.cart') }}" class="btn btn-secondary">
        Cart ({{ $cartCount }})
    </a>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to Shop</a>
@else
    <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to Products</a>
@endif
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="product-detail-grid">
            <div class="product-detail-media">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                @else
                    <div class="product-detail-placeholder">No Image</div>
                @endif
            </div>

            <div class="product-detail-main">
                <p class="product-detail-store">Store: {{ optional($product->vendor)->name ?? 'Local Store' }}</p>
                <h2>{{ $product->name }}</h2>
                <p class="product-detail-price">P{{ number_format($product->price, 2) }}</p>

                <div class="product-detail-meta">
                    <span>{{ $product->category ?? 'Souvenir' }}</span>
                    <span>{{ $product->stock > 0 ? $product->stock . ' in stock' : 'Limited stock' }}</span>
                </div>

                <p class="product-detail-description">
                    {{ $product->description ?: 'No additional description provided for this product yet.' }}
                </p>

                @if(Auth::user()->isTourist())
                <div class="product-detail-actions">
                    <form action="{{ route('products.cart.add', $product) }}" method="POST" class="product-qty-form">
                        @csrf
                        <label for="quantity">Qty</label>
                        <input type="number" id="quantity" name="quantity" min="1" value="1" {{ $product->stock ? 'max=' . $product->stock : '' }}>
                        <button type="submit" class="btn btn-secondary">Add to Cart</button>
                    </form>
                    <a href="{{ route('orders.create', ['product_id' => $product->id]) }}" class="btn btn-primary">Buy Now</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
.product-detail-grid {
    display: grid;
    grid-template-columns: minmax(260px, 360px) 1fr;
    gap: 1.5rem;
}

.product-detail-media {
    border-radius: 14px;
    overflow: hidden;
    background: #fff7fb;
    min-height: 280px;
}

.product-detail-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-detail-placeholder {
    min-height: 280px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #a16285;
}

.product-detail-store {
    font-size: 0.9rem;
    color: #7a4d63;
}

.product-detail-main h2 {
    color: #3b1f2e;
    margin: 0.45rem 0;
}

.product-detail-price {
    color: #be185d;
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 0.8rem;
}

.product-detail-meta {
    display: flex;
    gap: 0.6rem;
    flex-wrap: wrap;
    margin-bottom: 0.9rem;
}

.product-detail-meta span {
    font-size: 0.78rem;
    color: #9d174d;
    background: #fff1f7;
    border-radius: 999px;
    padding: 0.24rem 0.65rem;
}

.product-detail-description {
    color: #7a4d63;
    line-height: 1.6;
}

.product-detail-actions {
    margin-top: 1.3rem;
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.product-qty-form {
    display: flex;
    align-items: center;
    gap: 0.45rem;
}

.product-qty-form input {
    width: 80px;
    padding: 0.5rem;
    border: 1px solid #f4c5dc;
    border-radius: 8px;
}

@media (max-width: 900px) {
    .product-detail-grid {
        grid-template-columns: 1fr;
    }
}
@endsection

@extends('layouts.dashboard')

@section('title', Auth::user()->isTourist() ? 'Shop' : 'Products')
@section('page-title', Auth::user()->isTourist() ? '' : 'Products')

@section('header-actions')
@if(!Auth::user()->isTourist())
<a href="{{ route('products.create') }}" class="btn btn-primary">
    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>
    Add New
</a>
@endif
@endsection

@section('content')
@php
    $pendingProducts = \App\Models\Product::where('is_approved', false)->whereNull('rejection_reason')->count();
@endphp

@if(Auth::user()->isAdmin() && $pendingProducts > 0)
<!-- Pending Approval Alert -->
<div class="card" style="background: #fef3c7; border-left: 4px solid #f59e0b; margin-bottom: 1.5rem;">
    <div class="card-body" style="padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <svg width="24" height="24" fill="none" stroke="#92400e" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <strong style="color: #92400e;">{{ $pendingProducts }} product(s) waiting for approval</strong>
                <p style="margin: 0; font-size: 0.85rem; color: #78350f;">Review and approve or reject product listings.</p>
            </div>
        </div>
    </div>
</div>
@endif

@if(Auth::user()->isTourist())
    <section class="shop-hero-header">
        <div class="shop-hero-topline">
            <div class="shop-topline-left">
                <span>Seller Centre</span>
                <span>Start Selling</span>
                <span>Download</span>
            </div>
            <div class="shop-topline-right">
                <span>Hello, {{ Auth::user()->name }}</span>
                <span>{{ $products->total() }} Result(s)</span>
            </div>
        </div>

        <div class="shop-hero-main">
            <div class="shop-logo-wrap">
                <div class="shop-logo-mark">S</div>
                <div class="shop-logo-text">DiscoverShop</div>
            </div>

            <form action="{{ route('products.index') }}" method="GET" class="shop-search-form">
                @if($activeStore)
                    <input type="hidden" name="vendor" value="{{ $activeStore->id }}">
                @endif
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    class="shop-search-input"
                    placeholder="Search souvenir, pasalubong, slippers..."
                >
                <button type="submit" class="shop-search-btn" aria-label="Search products">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </form>

            <a href="{{ route('products.cart') }}" class="shop-cart-btn" aria-label="My cart">
                <svg width="30" height="30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2m0 0L7 13h10l3-8H5.4zM7 13l-1.5 6h13M9 19a1 1 0 100 2 1 1 0 000-2zm8 0a1 1 0 100 2 1 1 0 000-2z"/>
                </svg>
                @if(($cartCount ?? 0) > 0)
                    <span class="shop-cart-count">{{ $cartCount }}</span>
                @endif
            </a>
        </div>

        <div class="shop-hero-tags">
            <a href="{{ route('products.index', array_filter(['q' => 'Slippers', 'vendor' => request('vendor')])) }}">Slippers</a>
            <a href="{{ route('products.index', array_filter(['q' => 'Souvenir', 'vendor' => request('vendor')])) }}">Souvenirs</a>
            <a href="{{ route('products.index', array_filter(['q' => 'Handmade', 'vendor' => request('vendor')])) }}">Handmade</a>
            <a href="{{ route('products.index', array_filter(['q' => 'Pasalubong', 'vendor' => request('vendor')])) }}">Pasalubong</a>
            <a href="{{ route('products.index', array_filter(['q' => 'Beach', 'vendor' => request('vendor')])) }}">Beach Finds</a>
        </div>

    </section>

    <div class="shop-toolbar">
        <div>
            <h3>Souvenirs & Products</h3>
            <p>Browse items from local stores and visit each store to view all products.</p>
        </div>
        @if($activeStore)
            <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">Clear Store Filter</a>
        @endif
    </div>

    @if($activeStore)
    <div class="active-store-panel">
        <div class="active-store-logo">
            @if($activeStore->logo)
                <img src="{{ asset('storage/' . $activeStore->logo) }}" alt="{{ $activeStore->name }}">
            @else
                {{ strtoupper(substr($activeStore->name, 0, 1)) }}
            @endif
        </div>
        <div class="active-store-content">
            <h4>{{ $activeStore->name }}</h4>
            <p>{{ $activeStore->description ?: 'Local store with curated products and souvenirs.' }}</p>
            <div class="active-store-meta">
                <span>Type: {{ str_replace('_', ' ', $activeStore->type ?? 'other') }}</span>
                @if($activeStore->address)
                    <span>Address: {{ $activeStore->address }}</span>
                @endif
                @if($activeStore->contact_number)
                    <span>Contact: {{ $activeStore->contact_number }}</span>
                @endif
            </div>
        </div>
    </div>
    @endif

    <div class="shop-filter-bar">
        <div class="shop-category-chips">
            <a href="{{ route('products.index', array_filter(['vendor' => request('vendor'), 'q' => request('q'), 'sort' => $selectedSort])) }}" class="shop-chip {{ !$selectedCategory ? 'active' : '' }}">All</a>
            @foreach($categoryCounts as $categoryItem)
                <a
                    href="{{ route('products.index', array_filter(['vendor' => request('vendor'), 'q' => request('q'), 'sort' => $selectedSort, 'category' => $categoryItem->category_label])) }}"
                    class="shop-chip {{ $selectedCategory === $categoryItem->category_label ? 'active' : '' }}"
                >
                    {{ $categoryItem->category_label }} ({{ $categoryItem->total }})
                </a>
            @endforeach
        </div>

        <form method="GET" action="{{ route('products.index') }}" class="shop-sort-form">
            @if(request('vendor'))
                <input type="hidden" name="vendor" value="{{ request('vendor') }}">
            @endif
            @if(request('q'))
                <input type="hidden" name="q" value="{{ request('q') }}">
            @endif
            @if($selectedCategory)
                <input type="hidden" name="category" value="{{ $selectedCategory }}">
            @endif
            <label for="sort">Sort:</label>
            <select id="sort" name="sort" onchange="this.form.submit()">
                <option value="latest" {{ $selectedSort === 'latest' ? 'selected' : '' }}>Latest</option>
                <option value="oldest" {{ $selectedSort === 'oldest' ? 'selected' : '' }}>Oldest</option>
                <option value="price_asc" {{ $selectedSort === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="price_desc" {{ $selectedSort === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                <option value="name_asc" {{ $selectedSort === 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                <option value="name_desc" {{ $selectedSort === 'name_desc' ? 'selected' : '' }}>Name: Z to A</option>
            </select>
        </form>
    </div>

    @if($storeHighlights->count() > 0)
    <div class="shop-stores-row">
        @foreach($storeHighlights as $store)
            <article class="shop-store-chip {{ $activeStore && $activeStore->id === $store->id ? 'active' : '' }}">
                <div class="store-chip-logo">
                    @if($store->logo)
                        <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}">
                    @else
                        {{ strtoupper(substr($store->name, 0, 1)) }}
                    @endif
                </div>
                <div class="store-chip-meta">
                    <strong>{{ $store->name }}</strong>
                    <span>{{ $store->active_products_count }} item(s)</span>
                </div>
                <a href="{{ route('products.index', ['vendor' => $store->id]) }}" class="btn btn-sm btn-secondary">Visit Store</a>
            </article>
        @endforeach
    </div>
    @endif

    @if($products->count() > 0)
        <div class="shop-product-list">
            @foreach($products as $product)
                <article class="shop-product-card">
                    <aside class="shop-seller-side">
                        <div class="seller-logo">
                            @if(optional($product->vendor)->logo)
                                <img src="{{ asset('storage/' . $product->vendor->logo) }}" alt="{{ $product->vendor->name }}">
                            @else
                                {{ strtoupper(substr(optional($product->vendor)->name ?? 'S', 0, 1)) }}
                            @endif
                        </div>
                        <h4>{{ optional($product->vendor)->name ?? 'Local Store' }}</h4>
                        <p>{{ str_replace('_', ' ', optional($product->vendor)->type ?? 'other') }}</p>
                        <a href="{{ route('products.index', ['vendor' => $product->vendor_id]) }}" class="visit-store-link">Visit Store</a>
                    </aside>

                    <div class="shop-product-main">
                        <button type="button" class="shop-product-image-wrap shop-detail-trigger" data-target="shop-detail-{{ $product->id }}" aria-expanded="false">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="shop-product-image">
                            @else
                                <div class="shop-product-placeholder">No Image</div>
                            @endif
                        </button>

                        <div class="shop-product-content">
                            <h3>
                                <button type="button" class="shop-product-title-link shop-detail-trigger" data-target="shop-detail-{{ $product->id }}" aria-expanded="false">
                                    {{ $product->name }}
                                </button>
                            </h3>
                            <p>{{ \Illuminate\Support\Str::limit($product->description ?? 'Authentic local souvenir and pasalubong item.', 120) }}</p>
                            <div class="shop-product-meta">
                                <span>{{ $product->category ?? 'Souvenir' }}</span>
                                <span>{{ $product->stock > 0 ? $product->stock . ' in stock' : 'Limited stock' }}</span>
                            </div>
                        </div>

                        <div class="shop-product-side">
                            <div class="shop-price">₱{{ number_format($product->price, 2) }}</div>
                            <div class="shop-product-btns">
                                <form action="{{ route('products.cart.add', $product) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary btn-sm">Add to Cart</button>
                                </form>
                                <a href="{{ route('orders.create', ['product_id' => $product->id]) }}" class="btn btn-primary btn-sm">Buy Now</a>
                            </div>
                        </div>
                    </div>

                    <div id="shop-detail-{{ $product->id }}" class="shop-detail-panel" hidden>
                        <div class="shop-detail-grid">
                            <div>
                                <h4>Product Details</h4>
                                <p>{{ $product->description ?: 'No additional description provided for this product yet.' }}</p>
                            </div>
                            <div class="shop-detail-meta">
                                <p><strong>Store:</strong> {{ optional($product->vendor)->name ?? 'Local Store' }}</p>
                                <p><strong>Category:</strong> {{ $product->category ?? 'Souvenir' }}</p>
                                <p><strong>Price:</strong> ₱{{ number_format($product->price, 2) }}</p>
                                <p><strong>Stock:</strong> {{ $product->stock > 0 ? $product->stock : 'Limited' }}</p>
                                <p><strong>Uploaded:</strong> {{ optional($product->created_at)->format('M d, Y h:i A') }}</p>
                                @if(optional($product->vendor)->contact_number)
                                    <p><strong>Store Contact:</strong> {{ $product->vendor->contact_number }}</p>
                                @endif
                                @if(optional($product->vendor)->address)
                                    <p><strong>Store Address:</strong> {{ $product->vendor->address }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
        <div style="padding: 1rem 0;">{{ $products->links() }}</div>
    @else
        <div class="empty-state">
            @if($activeStore)
                <p>No products available for {{ $activeStore->name }} right now</p>
                <a href="{{ route('products.index') }}" class="btn btn-secondary" style="margin-top: 1rem;">Browse All Stores</a>
            @else
                <p>No products or souvenirs available yet</p>
            @endif
        </div>
    @endif

@else
    <div class="card">
        <div class="card-body" style="padding: 0;">
            @if($products->count() > 0)
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Vendor</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Approval</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 45px; height: 45px; object-fit: cover; border-radius: 8px;">
                                    @else
                                    <div style="width: 45px; height: 45px; background: #e5e7eb; border-radius: 8px;"></div>
                                    @endif
                                    <strong>{{ $product->name }}</strong>
                                </div>
                            </td>
                            <td>{{ $product->vendor->name ?? 'N/A' }}</td>
                            <td>{{ $product->category ?? 'N/A' }}</td>
                            <td>₱{{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->stock ?? 'N/A' }}</td>
                            <td>
                                <span class="status-badge {{ $product->is_available ? 'status-active' : 'status-inactive' }}">
                                    {{ $product->is_available ? 'Available' : 'Unavailable' }}
                                </span>
                            </td>
                            <td>
                                @if($product->is_approved)
                                    <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 500; background: #ffe3f1; color: #db2777;">Approved</span>
                                @elseif($product->rejection_reason)
                                    <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 500; background: #fee2e2; color: #dc2626;" title="{{ $product->rejection_reason }}">Rejected</span>
                                @else
                                    <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 500; background: #fef3c7; color: #92400e;">Pending</span>
                                @endif
                            </td>
                            <td>
                                <div class="table-actions" style="flex-wrap: wrap;">
                                    @if(Auth::user()->isAdmin() && !$product->is_approved && !$product->rejection_reason)
                                        {{-- Pending approval - show approve/reject --}}
                                        <form action="{{ route('products.approve', $product) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm" style="background: #db2777; color: white;">Approve</button>
                                        </form>
                                        <form action="{{ route('products.reject', $product) }}" method="POST" style="display: inline;" onsubmit="return confirm('Reject this product listing?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                        </form>
                                    @endif
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-secondary btn-sm">Edit</a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="padding: 1rem;">{{ $products->links() }}</div>
            @else
            <div class="empty-state">
                <p>No products yet</p>
                <a href="{{ route('products.create') }}" class="btn btn-primary" style="margin-top: 1rem;">Add First Product</a>
            </div>
            @endif
        </div>
    </div>
@endif
@endsection

@section('styles')
@if(Auth::user()->isTourist())
.shop-hero-header {
    background: linear-gradient(180deg, #db2777 0%, #ec4899 100%);
    border-radius: 14px;
    padding: 0.7rem 1rem 1rem;
    margin-bottom: 1rem;
    color: #fff;
    box-shadow: 0 8px 22px rgba(236, 72, 153, 0.28);
}

.shop-hero-topline {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.78rem;
    opacity: 0.95;
}

.shop-topline-left,
.shop-topline-right {
    display: flex;
    gap: 0.7rem;
    flex-wrap: wrap;
}

.shop-hero-main {
    display: grid;
    grid-template-columns: auto 1fr auto;
    align-items: center;
    gap: 1rem;
    margin-top: 0.6rem;
}

.shop-logo-wrap {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.shop-logo-mark {
    width: 42px;
    height: 42px;
    background: #fff;
    color: #db2777;
    border-radius: 7px;
    font-size: 1.8rem;
    font-style: italic;
    line-height: 1;
    display: flex;
    align-items: center;
    justify-content: center;
}

.shop-logo-text {
    font-size: 2rem;
    font-weight: 600;
    letter-spacing: 0.2px;
}

.shop-search-form {
    display: grid;
    grid-template-columns: 1fr 56px;
    background: #fff;
    border-radius: 4px;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.7);
}

.shop-search-input {
    border: none;
    font-size: 1.5rem;
    padding: 0.85rem 1rem;
    color: #2b2b2b;
}

.shop-search-input:focus {
    outline: none;
}

.shop-search-btn {
    border: none;
    background: #db2777;
    color: #fff;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.shop-search-btn:hover {
    background: #be185d;
}

.shop-cart-btn {
    color: #fff;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    border-radius: 10px;
    position: relative;
}

.shop-cart-count {
    position: absolute;
    top: -4px;
    right: -4px;
    min-width: 18px;
    height: 18px;
    border-radius: 999px;
    background: #fff;
    color: #be185d;
    font-size: 0.72rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0 0.28rem;
}

.shop-cart-btn:hover {
    background: rgba(255, 255, 255, 0.14);
}

.shop-hero-tags {
    display: flex;
    gap: 0.95rem;
    flex-wrap: wrap;
    margin-top: 0.55rem;
    padding-left: 52px;
}

.shop-hero-tags a {
    color: rgba(255, 255, 255, 0.92);
    text-decoration: none;
    font-size: 0.88rem;
}

.shop-hero-tags a:hover {
    color: #fff;
    text-decoration: underline;
}

.shop-toolbar {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1rem;
}

.shop-toolbar h3 {
    font-size: 1.15rem;
    color: #be185d;
}

.shop-toolbar p {
    font-size: 0.88rem;
    color: #7a4d63;
    margin-top: 0.35rem;
}

.active-store-panel {
    background: #fff;
    border: 1px solid #f4c5dc;
    border-radius: 14px;
    padding: 0.95rem;
    margin-bottom: 0.9rem;
    display: grid;
    grid-template-columns: 76px 1fr;
    gap: 0.85rem;
}

.active-store-logo {
    width: 76px;
    height: 76px;
    border-radius: 50%;
    border: 1px solid #f4c5dc;
    background: #fff1f7;
    color: #be185d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.55rem;
    font-weight: 700;
    overflow: hidden;
}

.active-store-logo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.active-store-content h4 {
    color: #be185d;
}

.active-store-content p {
    color: #7a4d63;
    margin: 0.25rem 0 0.55rem;
}

.active-store-meta {
    display: flex;
    gap: 0.45rem;
    flex-wrap: wrap;
}

.active-store-meta span {
    background: #fff1f7;
    color: #9d174d;
    border-radius: 999px;
    padding: 0.22rem 0.58rem;
    font-size: 0.74rem;
}

.shop-filter-bar {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 0.9rem;
    margin-bottom: 0.85rem;
}

.shop-category-chips {
    display: flex;
    gap: 0.42rem;
    flex-wrap: wrap;
}

.shop-chip {
    text-decoration: none;
    color: #9d174d;
    background: #fff1f7;
    border-radius: 999px;
    padding: 0.3rem 0.7rem;
    font-size: 0.8rem;
    border: 1px solid #ffd6ea;
}

.shop-chip.active {
    background: #db2777;
    border-color: #db2777;
    color: #fff;
}

.shop-sort-form {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
}

.shop-sort-form label {
    color: #7a4d63;
    font-size: 0.82rem;
}

.shop-sort-form select {
    border: 1px solid #f4c5dc;
    border-radius: 8px;
    padding: 0.34rem 0.55rem;
    color: #3b1f2e;
    background: #fff;
}

.shop-stores-row {
    display: grid;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.shop-store-chip {
    background: #fff;
    border: 1px solid #f4c5dc;
    border-radius: 12px;
    padding: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.shop-store-chip.active {
    border-color: #db2777;
    box-shadow: 0 0 0 2px rgba(219, 39, 119, 0.15);
}

.store-chip-logo {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #fff1f7;
    color: #be185d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    overflow: hidden;
    flex-shrink: 0;
}

.store-chip-logo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.store-chip-meta {
    flex: 1;
}

.store-chip-meta strong {
    display: block;
    font-size: 0.95rem;
    color: #3b1f2e;
}

.store-chip-meta span {
    font-size: 0.78rem;
    color: #7a4d63;
}

.shop-product-list {
    display: grid;
    gap: 1rem;
}

.shop-product-card {
    background: #fff;
    border: 1px solid #f4c5dc;
    border-radius: 16px;
    overflow: hidden;
    display: grid;
    grid-template-columns: 210px 1fr;
}

.shop-seller-side {
    border-right: 1px solid #fde7f3;
    background: linear-gradient(180deg, #fff7fb 0%, #fff 100%);
    padding: 1rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.seller-logo {
    width: 58px;
    height: 58px;
    border-radius: 50%;
    background: #fff;
    border: 1px solid #f4c5dc;
    color: #be185d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    font-weight: 700;
    overflow: hidden;
    margin-bottom: 0.75rem;
}

.seller-logo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.shop-seller-side h4 {
    font-size: 0.95rem;
    color: #3b1f2e;
}

.shop-seller-side p {
    margin: 0.3rem 0 0.75rem;
    font-size: 0.75rem;
    color: #7a4d63;
    text-transform: capitalize;
}

.visit-store-link {
    display: inline-block;
    font-size: 0.8rem;
    color: #be185d;
    text-decoration: none;
    border: 1px solid #f4c5dc;
    padding: 0.35rem 0.75rem;
    border-radius: 999px;
}

.visit-store-link:hover {
    background: #fff1f7;
}

.shop-product-main {
    padding: 1rem;
    display: grid;
    grid-template-columns: 140px 1fr auto;
    align-items: center;
    gap: 1rem;
}

.shop-product-image-wrap {
    width: 140px;
    height: 140px;
    border-radius: 12px;
    overflow: hidden;
    background: #fff7fb;
    border: none;
    cursor: pointer;
    padding: 0;
}

.shop-product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.shop-product-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #a16285;
    font-size: 0.8rem;
}

.shop-product-content h3 {
    font-size: 1.06rem;
    color: #3b1f2e;
    margin-bottom: 0.45rem;
}

.shop-product-title-link {
    color: inherit;
    text-decoration: none;
    border: none;
    background: transparent;
    font: inherit;
    padding: 0;
    cursor: pointer;
    text-align: left;
}

.shop-product-title-link:hover {
    color: #be185d;
    text-decoration: underline;
}

.shop-product-content p {
    font-size: 0.86rem;
    color: #7a4d63;
    line-height: 1.45;
}

.shop-product-meta {
    display: flex;
    gap: 0.55rem;
    margin-top: 0.65rem;
    flex-wrap: wrap;
}

.shop-product-meta span {
    font-size: 0.74rem;
    color: #9d174d;
    background: #fff1f7;
    border-radius: 999px;
    padding: 0.24rem 0.6rem;
}

.shop-product-side {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.7rem;
}

.shop-product-btns {
    display: flex;
    gap: 0.45rem;
    flex-wrap: wrap;
    justify-content: flex-end;
}

.shop-detail-panel {
    border-top: 1px solid #fde7f3;
    background: #fffafc;
    padding: 1rem;
}

.shop-detail-grid {
    display: grid;
    grid-template-columns: 1fr 260px;
    gap: 1rem;
}

.shop-detail-grid h4 {
    color: #be185d;
    margin-bottom: 0.45rem;
}

.shop-detail-grid p {
    color: #7a4d63;
    line-height: 1.55;
}

.shop-detail-meta {
    background: #fff1f7;
    border-radius: 10px;
    padding: 0.8rem;
}

.shop-detail-meta p {
    margin-bottom: 0.35rem;
}

.shop-price {
    color: #be185d;
    font-size: 1.35rem;
    font-weight: 700;
}

@media (max-width: 980px) {
    .shop-hero-main {
        grid-template-columns: 1fr;
    }

    .shop-logo-text {
        font-size: 1.55rem;
    }

    .shop-search-input {
        font-size: 1rem;
    }

    .shop-hero-tags {
        padding-left: 0;
    }

    .shop-product-card {
        grid-template-columns: 1fr;
    }

    .active-store-panel {
        grid-template-columns: 1fr;
    }

    .shop-filter-bar {
        flex-direction: column;
        align-items: stretch;
    }

    .shop-seller-side {
        border-right: none;
        border-bottom: 1px solid #fde7f3;
        flex-direction: row;
        justify-content: space-between;
        text-align: left;
    }

    .shop-seller-side p {
        margin: 0.2rem 0 0;
    }

    .shop-product-main {
        grid-template-columns: 110px 1fr;
    }

    .shop-product-image-wrap {
        width: 110px;
        height: 110px;
    }

    .shop-product-side {
        grid-column: 1 / -1;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }

    .shop-detail-grid {
        grid-template-columns: 1fr;
    }

}
@endif
@endsection

@section('scripts')
@if(Auth::user()->isTourist())
<script>
document.addEventListener('DOMContentLoaded', function () {
    const detailTriggers = document.querySelectorAll('.shop-detail-trigger');

    detailTriggers.forEach(function (trigger) {
        trigger.addEventListener('click', function () {
            const panelId = trigger.getAttribute('data-target');
            const panel = document.getElementById(panelId);
            if (!panel) {
                return;
            }

            if (panel.hasAttribute('hidden')) {
                panel.removeAttribute('hidden');
                trigger.setAttribute('aria-expanded', 'true');
            } else {
                panel.setAttribute('hidden', 'hidden');
                trigger.setAttribute('aria-expanded', 'false');
            }
        });
    });
});
</script>
@endif
@endsection

@extends('layouts.dashboard')

@section('title', 'Place Order')
@section('page-title', Auth::user()->isTourist() ? '' : 'Place New Order')

@section('header-actions')
<a href="{{ route('orders.index') }}" class="btn btn-secondary">
    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
    </svg>
    Back to Orders
</a>
@endsection

@section('content')
<section class="order-create-hero">
    <div>
        <h2>Place New Order</h2>
        <p>Select product, quantity, and shipping details to continue.</p>
    </div>
</section>

<div class="order-create-wrap">
    <div class="card order-create-main">
        <div class="card-header">
            <h3>Order Details</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('orders.store') }}" method="POST" class="order-create-form">
                @csrf

                <div class="form-group">
                    <label class="form-label">Product <span class="required-mark">*</span></label>
                    <select name="product_id" class="form-input" required>
                        <option value="">Select a product...</option>
                        @foreach($products as $product)
                        <option value="{{ $product->id }}"
                                data-price="{{ $product->price }}"
                                data-vendor="{{ $product->vendor_id }}"
                                {{ old('product_id', request('product_id')) == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} - ₱{{ number_format($product->price, 2) }} ({{ $product->vendor->name ?? 'Unknown Vendor' }})
                        </option>
                        @endforeach
                    </select>
                    @error('product_id')
                    <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Quantity <span class="required-mark">*</span></label>
                    <input type="number" name="quantity" class="form-input" value="{{ old('quantity', 1) }}" min="1" required>
                    @error('quantity')
                    <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div id="price-preview" class="order-preview" style="display: none;">
                    <div class="order-preview-row">
                        <span>Unit Price</span>
                        <strong id="unit-price">₱0.00</strong>
                    </div>
                    <div class="order-preview-row">
                        <span>Quantity</span>
                        <strong id="qty-display">1</strong>
                    </div>
                    <div class="order-preview-total">
                        <span>Total</span>
                        <strong id="total-price">₱0.00</strong>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Shipping Address <span class="required-mark">*</span></label>
                    <textarea name="shipping_address" class="form-input" rows="3" required placeholder="Enter your complete shipping address">{{ old('shipping_address', Auth::user()->address ?? '') }}</textarea>
                    @error('shipping_address')
                    <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Contact Number <span class="required-mark">*</span></label>
                    <input type="text" name="contact_number" class="form-input" value="{{ old('contact_number') }}" required placeholder="09XX XXX XXXX">
                    @error('contact_number')
                    <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Order Notes (Optional)</label>
                    <textarea name="notes" class="form-input" rows="2" placeholder="Any special instructions or notes for your order">{{ old('notes') }}</textarea>
                    @error('notes')
                    <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="order-form-actions">
                    <button type="submit" class="btn btn-primary">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        Place Order
                    </button>
                    <a href="{{ route('orders.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <aside class="order-create-side">
        <div class="order-tip-card">
            <h4>Checkout Tips</h4>
            <ul>
                <li>Double-check quantity and total.</li>
                <li>Use complete shipping address for faster delivery.</li>
                <li>Keep your contact number active for rider updates.</li>
            </ul>
        </div>
    </aside>
</div>
@endsection

@section('styles')
.order-create-hero {
    background: linear-gradient(180deg, #db2777 0%, #ec4899 100%);
    border-radius: 12px;
    padding: 0.9rem 1rem;
    color: #fff;
    margin-bottom: 1rem;
}

.order-create-hero h2 {
    font-size: 1.2rem;
    color: #fff;
}

.order-create-hero p {
    margin-top: 0.2rem;
    font-size: 0.82rem;
    opacity: 0.92;
}

.order-create-wrap {
    display: grid;
    grid-template-columns: minmax(320px, 760px) 260px;
    gap: 1rem;
    align-items: start;
}

.order-create-main {
    width: 100%;
}

.required-mark {
    color: #dc2626;
}

.form-error {
    color: #dc2626;
    font-size: 0.83rem;
}

.order-preview {
    padding: 0.9rem;
    border-radius: 10px;
    background: #fff1f7;
    border: 1px solid #ffd6ea;
    margin-bottom: 1rem;
}

.order-preview-row,
.order-preview-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.order-preview-row {
    margin-bottom: 0.45rem;
    color: #7a4d63;
}

.order-preview-total {
    border-top: 1px solid #f4c5dc;
    padding-top: 0.55rem;
    margin-top: 0.15rem;
    color: #be185d;
}

.order-preview-total strong {
    font-size: 1.25rem;
}

.order-form-actions {
    display: flex;
    gap: 0.75rem;
    margin-top: 1.2rem;
    flex-wrap: wrap;
}

.order-create-side {
    position: sticky;
    top: 88px;
}

.order-tip-card {
    background: #fff;
    border: 1px solid #f4c5dc;
    border-radius: 12px;
    padding: 0.85rem;
}

.order-tip-card h4 {
    color: #be185d;
    margin-bottom: 0.55rem;
}

.order-tip-card ul {
    margin: 0;
    padding-left: 1rem;
    color: #7a4d63;
    display: grid;
    gap: 0.35rem;
    font-size: 0.84rem;
}

@media (max-width: 980px) {
    .order-create-wrap {
        grid-template-columns: 1fr;
    }

    .order-create-side {
        position: static;
    }
}
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const productSelect = document.querySelector('select[name="product_id"]');
    const quantityInput = document.querySelector('input[name="quantity"]');
    const pricePreview = document.getElementById('price-preview');
    const unitPriceEl = document.getElementById('unit-price');
    const qtyDisplayEl = document.getElementById('qty-display');
    const totalPriceEl = document.getElementById('total-price');

    function updatePrice() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const price = parseFloat(selectedOption.dataset.price) || 0;
        const quantity = parseInt(quantityInput.value) || 1;
        
        if (price > 0) {
            pricePreview.style.display = 'block';
            unitPriceEl.textContent = '₱' + price.toLocaleString('en-PH', {minimumFractionDigits: 2});
            qtyDisplayEl.textContent = quantity;
            totalPriceEl.textContent = '₱' + (price * quantity).toLocaleString('en-PH', {minimumFractionDigits: 2});
        } else {
            pricePreview.style.display = 'none';
        }
    }

    productSelect.addEventListener('change', updatePrice);
    quantityInput.addEventListener('input', updatePrice);
    
    // Initialize
    updatePrice();
});
</script>
@endsection


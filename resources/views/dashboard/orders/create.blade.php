@extends('layouts.dashboard')

@section('title', 'Place Order')
@section('page-title', 'Place New Order')

@section('header-actions')
<a href="{{ route('orders.index') }}" class="btn btn-secondary">
    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
    </svg>
    Back to Orders
</a>
@endsection

@section('content')
<div class="card" style="max-width: 700px;">
    <div class="card-header">
        <h3>Order Details</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('orders.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Product <span style="color: #dc2626;">*</span></label>
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
                <span style="color: #dc2626; font-size: 0.85rem;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Quantity <span style="color: #dc2626;">*</span></label>
                <input type="number" name="quantity" class="form-input" value="{{ old('quantity', 1) }}" min="1" required>
                @error('quantity')
                <span style="color: #dc2626; font-size: 0.85rem;">{{ $message }}</span>
                @enderror
            </div>

            <div id="price-preview" style="padding: 1rem; background: #fff1f7; border-radius: 8px; margin-bottom: 1rem; display: none;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="color: #6b7280;">Unit Price:</span>
                    <span id="unit-price" style="font-weight: 500;">₱0.00</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="color: #6b7280;">Quantity:</span>
                    <span id="qty-display" style="font-weight: 500;">1</span>
                </div>
                <hr style="border: none; border-top: 1px solid #d1fae5; margin: 0.5rem 0;">
                <div style="display: flex; justify-content: space-between;">
                    <span style="font-weight: 600; color: #be185d;">Total:</span>
                    <span id="total-price" style="font-weight: 700; font-size: 1.2rem; color: #be185d;">₱0.00</span>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Shipping Address <span style="color: #dc2626;">*</span></label>
                <textarea name="shipping_address" class="form-input" rows="3" required placeholder="Enter your complete shipping address">{{ old('shipping_address', Auth::user()->address ?? '') }}</textarea>
                @error('shipping_address')
                <span style="color: #dc2626; font-size: 0.85rem;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Contact Number <span style="color: #dc2626;">*</span></label>
                <input type="text" name="contact_number" class="form-input" value="{{ old('contact_number') }}" required placeholder="09XX XXX XXXX">
                @error('contact_number')
                <span style="color: #dc2626; font-size: 0.85rem;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Order Notes (Optional)</label>
                <textarea name="notes" class="form-input" rows="2" placeholder="Any special instructions or notes for your order">{{ old('notes') }}</textarea>
                @error('notes')
                <span style="color: #dc2626; font-size: 0.85rem;">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
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


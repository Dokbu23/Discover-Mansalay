@extends('layouts.dashboard')

@section('title', 'Send Inquiry')
@section('page-title', 'Send New Inquiry')

@section('header-actions')
<a href="{{ route('inquiries.index') }}" class="btn btn-secondary">
    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
    </svg>
    Back to Inquiries
</a>
@endsection

@section('content')
<div class="card" style="max-width: 700px;">
    <div class="card-header">
        <h3>Inquiry Details</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('inquiries.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Select Vendor <span style="color: #dc2626;">*</span></label>
                <select name="vendor_id" id="vendor_id" class="form-input" required>
                    <option value="">Choose a vendor...</option>
                    @foreach($vendors as $vendor)
                    <option value="{{ $vendor->id }}" {{ old('vendor_id', request('vendor_id')) == $vendor->id ? 'selected' : '' }}>
                        {{ $vendor->name }}
                    </option>
                    @endforeach
                </select>
                @error('vendor_id')
                <span style="color: #dc2626; font-size: 0.85rem;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Product (Optional)</label>
                <select name="product_id" id="product_id" class="form-input">
                    <option value="">General inquiry / No specific product</option>
                    @foreach($products as $product)
                    <option value="{{ $product->id }}" 
                            data-vendor="{{ $product->vendor_id }}"
                            {{ old('product_id', request('product_id')) == $product->id ? 'selected' : '' }}>
                        {{ $product->name }} ({{ $product->vendor->name ?? 'Unknown' }})
                    </option>
                    @endforeach
                </select>
                @error('product_id')
                <span style="color: #dc2626; font-size: 0.85rem;">{{ $message }}</span>
                @enderror
                <small style="color: #6b7280;">If you're inquiring about a specific product, select it here.</small>
            </div>

            <div class="form-group">
                <label class="form-label">Subject <span style="color: #dc2626;">*</span></label>
                <input type="text" name="subject" class="form-input" value="{{ old('subject') }}" required placeholder="What is your inquiry about?">
                @error('subject')
                <span style="color: #dc2626; font-size: 0.85rem;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Message <span style="color: #dc2626;">*</span></label>
                <textarea name="message" class="form-input" rows="6" required placeholder="Type your message here...">{{ old('message') }}</textarea>
                @error('message')
                <span style="color: #dc2626; font-size: 0.85rem;">{{ $message }}</span>
                @enderror
            </div>

            <div style="background: #fff1f7; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                <div style="display: flex; gap: 0.5rem; align-items: flex-start;">
                    <svg width="20" height="20" fill="none" stroke="#be185d" viewBox="0 0 24 24" style="flex-shrink: 0; margin-top: 2px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <strong style="color: #be185d;">Note:</strong>
                        <p style="margin: 0.25rem 0 0; color: #374151; font-size: 0.9rem;">
                            The vendor will receive your inquiry and respond as soon as possible. 
                            You'll be notified when they reply.
                        </p>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Send Inquiry
                </button>
                <a href="{{ route('inquiries.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const vendorSelect = document.getElementById('vendor_id');
    const productSelect = document.getElementById('product_id');
    const productOptions = productSelect.querySelectorAll('option[data-vendor]');

    // When vendor changes, filter products
    vendorSelect.addEventListener('change', function() {
        const selectedVendor = this.value;
        
        productOptions.forEach(option => {
            if (!selectedVendor || option.dataset.vendor === selectedVendor) {
                option.style.display = '';
            } else {
                option.style.display = 'none';
                if (option.selected) {
                    productSelect.value = '';
                }
            }
        });
    });

    // When product changes, auto-select vendor
    productSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const vendorId = selectedOption.dataset.vendor;
        
        if (vendorId && vendorSelect.value !== vendorId) {
            vendorSelect.value = vendorId;
        }
    });

    // Initialize - trigger vendor change to filter products
    if (vendorSelect.value) {
        vendorSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection


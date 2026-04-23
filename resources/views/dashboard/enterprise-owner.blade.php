@extends('layouts.dashboard')

@section('title', 'Vendor Dashboard')
@section('page-title', 'My Business Dashboard')

@section('content')
<!-- Welcome Banner -->
<div class="card" style="background: linear-gradient(135deg, #7c3aed 0%, #a78bfa 100%); color: white; margin-bottom: 2rem;">
    <div class="card-body" style="padding: 2rem;">
        <h2 style="margin: 0 0 0.5rem 0; font-size: 1.5rem;">Welcome back, {{ Auth::user()->name }}!</h2>
        <p style="margin: 0; opacity: 0.9;">Manage your products and business here.</p>
    </div>
</div>

@php
    $vendorPaymentFee = config('payments.vendor_approval_fee', 0);
    $gcashName = config('payments.gcash_name', '');
    $gcashNumber = config('payments.gcash_number', '');
@endphp

@if(!Auth::user()->is_approved)
<!-- Vendor Approval Fee -->
<div class="card" style="margin-bottom: 2rem; border-left: 4px solid #0ea5e9;">
    <div class="card-body">
        <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; align-items: center; justify-content: space-between;">
            <div style="flex: 1; min-width: 260px;">
                <h3 style="margin: 0 0 0.5rem 0;">Vendor Approval Fee</h3>
                <p style="margin: 0 0 0.75rem 0; color: #6b7280;">Please pay the approval fee and upload your GCash receipt to proceed.</p>
                <div style="display: grid; gap: 0.35rem; font-size: 0.9rem;">
                    <div><strong>Fee:</strong> ₱{{ number_format($vendorPaymentFee, 2) }}</div>
                    <div><strong>GCash Name:</strong> {{ $gcashName }}</div>
                    <div><strong>GCash Number:</strong> {{ $gcashNumber }}</div>
                </div>
            </div>
            <div style="min-width: 260px;">
                @if(Auth::user()->hasVerifiedVendorPayment())
                    <span style="padding: 0.35rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; background: #dcfce7; color: #15803d;">Payment Verified</span>
                    <p style="margin: 0.5rem 0 0 0; color: #6b7280; font-size: 0.85rem;">Wait for admin approval.</p>
                @elseif(Auth::user()->hasSubmittedVendorPayment())
                    <span style="padding: 0.35rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; background: #dbeafe; color: #2563eb;">Receipt Submitted</span>
                    <p style="margin: 0.5rem 0 0 0; color: #6b7280; font-size: 0.85rem;">Admin will verify your payment.</p>
                @else
                    <form action="{{ route('vendor.payment.submit') }}" method="POST" enctype="multipart/form-data" style="display: grid; gap: 0.5rem;">
                        @csrf
                        <input type="file" name="receipt" class="form-input" accept=".jpg,.jpeg,.png,.pdf" required>
                        <button type="submit" class="btn btn-primary" style="background: #0ea5e9;">Done Payment</button>
                    </form>
                    <p style="margin: 0.5rem 0 0 0; color: #9ca3af; font-size: 0.8rem;">Accepted: JPG, PNG, PDF (max 5MB)</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

@if($vendor)
<!-- Vendor Info Card -->
<div class="card" style="margin-bottom: 2rem;">
    <div class="card-body">
        <div style="display: flex; align-items: center; gap: 1.5rem;">
            @if($vendor->logo)
                <img src="{{ asset('storage/' . $vendor->logo) }}" alt="{{ $vendor->name }}" style="width: 80px; height: 80px; border-radius: 12px; object-fit: cover;">
            @else
                <div style="width: 80px; height: 80px; border-radius: 12px; background: linear-gradient(135deg, #be185d, #db2777); display: flex; align-items: center; justify-content: center;">
                    <svg width="40" height="40" fill="none" stroke="white" viewBox="0 0 24 24" style="opacity: 0.7;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            @endif
            <div style="flex: 1;">
                <h3 style="margin: 0 0 0.25rem 0;">{{ $vendor->name }}</h3>
                <p style="margin: 0; color: #6b7280; font-size: 0.9rem;">{{ ucfirst(str_replace('_', ' ', $vendor->type)) }}</p>
                <p style="margin: 0.5rem 0 0 0; color: #6b7280; font-size: 0.85rem;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: inline; vertical-align: middle;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ $vendor->address ?? 'No address set' }}
                </p>
            </div>
            <a href="{{ route('vendors.edit', $vendor) }}" class="btn btn-secondary">Edit Profile</a>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid" style="margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-icon gold">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['products'] }}</h3>
            <p>Total Products</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['available_products'] }}</h3>
            <p>Available Products</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon blue">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['categories'] }}</h3>
            <p>Categories</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon purple">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['total_stock'] }}</h3>
            <p>Total Stock</p>
        </div>
    </div>
</div>

<!-- My Products -->
<div class="card">
    <div class="card-header">
        <h3>My Products</h3>
        <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Product
        </a>
    </div>
    <div class="card-body">
        @if($products->count() > 0)
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem;">
                @foreach($products as $product)
                <div class="card" style="border: 1px solid #e5e7eb; box-shadow: none;">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 100%; height: 120px; object-fit: cover; border-radius: 8px 8px 0 0;">
                    @else
                        <div style="width: 100%; height: 120px; background: linear-gradient(135deg, #f3f4f6, #e5e7eb); border-radius: 8px 8px 0 0; display: flex; align-items: center; justify-content: center;">
                            <svg width="32" height="32" fill="none" stroke="#9ca3af" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    @endif
                    <div style="padding: 1rem;">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                            <h4 style="margin: 0; font-size: 0.95rem;">{{ $product->name }}</h4>
                            @if($product->is_available)
                                <span class="status-badge status-confirmed" style="font-size: 0.65rem;">In Stock</span>
                            @else
                                <span class="status-badge status-cancelled" style="font-size: 0.65rem;">Out</span>
                            @endif
                        </div>
                        <p style="font-size: 0.75rem; color: #6b7280; margin-bottom: 0.5rem;">{{ $product->category }}</p>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                            <span style="font-weight: 700; color: #be185d;">₱{{ number_format($product->price, 2) }}</span>
                            <span style="font-size: 0.75rem; color: #6b7280;">Stock: {{ $product->stock }}</span>
                        </div>
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-secondary btn-sm" style="width: 100%;">Edit</a>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <p>You don't have any products yet</p>
                <a href="{{ route('products.create') }}" class="btn btn-primary" style="margin-top: 1rem;">Add Your First Product</a>
            </div>
        @endif
    </div>
</div>

@else
<!-- No Vendor Profile -->
<div class="card">
    <div class="card-body" style="text-align: center; padding: 3rem;">
        <svg width="64" height="64" fill="none" stroke="#9ca3af" viewBox="0 0 24 24" style="margin-bottom: 1rem;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
        <h3 style="margin: 0 0 0.5rem 0;">Set Up Your Business</h3>
        <p style="color: #6b7280; margin-bottom: 1.5rem;">Create your vendor profile to start adding products and reach tourists in Mansalay.</p>
        <a href="{{ route('vendors.create') }}" class="btn btn-primary btn-lg">Create Vendor Profile</a>
    </div>
</div>
@endif

@section('styles')
<style>
@media (max-width: 768px) {
    div[style*="grid-template-columns: repeat(auto-fill"] {
        grid-template-columns: 1fr 1fr !important;
    }
}
@media (max-width: 480px) {
    div[style*="grid-template-columns: repeat(auto-fill"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection
@endsection


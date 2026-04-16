@extends('layouts.dashboard')

@section('title', 'Products')
@section('page-title', 'Products')

@section('header-actions')
<a href="{{ route('products.create') }}" class="btn btn-primary">
    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>
    Add New
</a>
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
@endsection

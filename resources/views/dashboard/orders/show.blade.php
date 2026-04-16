@extends('layouts.dashboard')

@section('title', 'Order #' . $order->id)
@section('page-title', 'Order Details')

@section('header-actions')
<a href="{{ route('orders.index') }}" class="btn btn-secondary">
    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
    </svg>
    Back to Orders
</a>
@endsection

@section('content')
<div style="display: grid; gap: 1.5rem; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));">
    <!-- Order Info -->
    <div class="card">
        <div class="card-header">
            <h3>Order #{{ $order->id }}</h3>
            @php
                $statusColors = [
                    'pending' => 'background: #fef3c7; color: #92400e;',
                    'confirmed' => 'background: #dbeafe; color: #1e40af;',
                    'processing' => 'background: #e0e7ff; color: #3730a3;',
                    'shipped' => 'background: #fce7f3; color: #9d174d;',
                    'delivered' => 'background: #ffe3f1; color: #166534;',
                    'cancelled' => 'background: #fee2e2; color: #991b1b;',
                ];
            @endphp
            <span style="padding: 0.35rem 1rem; border-radius: 20px; font-size: 0.85rem; font-weight: 500; {{ $statusColors[$order->status] ?? '' }}">
                {{ ucfirst($order->status) }}
            </span>
        </div>
        <div class="card-body">
            <table style="width: 100%; border-collapse: collapse;">
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 0.75rem 0; color: #6b7280; width: 40%;">Order Date</td>
                    <td style="padding: 0.75rem 0; font-weight: 500;">{{ $order->created_at->format('F d, Y h:i A') }}</td>
                </tr>
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 0.75rem 0; color: #6b7280;">Last Updated</td>
                    <td style="padding: 0.75rem 0;">{{ $order->updated_at->format('F d, Y h:i A') }}</td>
                </tr>
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 0.75rem 0; color: #6b7280;">Quantity</td>
                    <td style="padding: 0.75rem 0; font-weight: 500;">{{ $order->quantity }}</td>
                </tr>
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 0.75rem 0; color: #6b7280;">Total Price</td>
                    <td style="padding: 0.75rem 0; font-weight: 600; font-size: 1.1rem; color: #be185d;">₱{{ number_format($order->total_price, 2) }}</td>
                </tr>
            </table>

            @if($order->notes)
            <div style="margin-top: 1rem; padding: 1rem; background: #f9fafb; border-radius: 8px;">
                <strong style="color: #374151;">Order Notes</strong>
                <p style="margin-top: 0.5rem; color: #6b7280;">{{ $order->notes }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Product Details -->
    <div class="card">
        <div class="card-header">
            <h3>Product Details</h3>
        </div>
        <div class="card-body">
            @if($order->product)
            <div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 1rem;">
                @if($order->product->image)
                <img src="{{ asset('storage/' . $order->product->image) }}" alt="{{ $order->product->name }}" 
                     style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                @else
                <div style="width: 80px; height: 80px; background: #e5e7eb; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <svg width="32" height="32" fill="none" stroke="#9ca3af" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                @endif
                <div>
                    <h4 style="margin: 0; color: #1f2937;">{{ $order->product->name }}</h4>
                    <p style="margin: 0.25rem 0 0; color: #6b7280; font-size: 0.9rem;">₱{{ number_format($order->product->price, 2) }} each</p>
                </div>
            </div>
            @if($order->product->description)
            <p style="color: #6b7280; font-size: 0.9rem;">{{ Str::limit($order->product->description, 100) }}</p>
            @endif
            @else
            <p style="color: #6b7280;">Product information unavailable</p>
            @endif
        </div>
    </div>

    <!-- Customer Information -->
    <div class="card">
        <div class="card-header">
            <h3>Customer Information</h3>
        </div>
        <div class="card-body">
            <table style="width: 100%; border-collapse: collapse;">
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 0.75rem 0; color: #6b7280; width: 40%;">Name</td>
                    <td style="padding: 0.75rem 0; font-weight: 500;">{{ $order->user->name ?? 'N/A' }}</td>
                </tr>
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 0.75rem 0; color: #6b7280;">Email</td>
                    <td style="padding: 0.75rem 0;">{{ $order->user->email ?? 'N/A' }}</td>
                </tr>
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 0.75rem 0; color: #6b7280;">Contact #</td>
                    <td style="padding: 0.75rem 0;">{{ $order->contact_number ?? 'Not provided' }}</td>
                </tr>
                <tr>
                    <td style="padding: 0.75rem 0; color: #6b7280; vertical-align: top;">Shipping Address</td>
                    <td style="padding: 0.75rem 0;">{{ $order->shipping_address ?? 'Not provided' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Vendor Information -->
    <div class="card">
        <div class="card-header">
            <h3>Vendor Information</h3>
        </div>
        <div class="card-body">
            @if($order->vendor)
            <table style="width: 100%; border-collapse: collapse;">
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 0.75rem 0; color: #6b7280; width: 40%;">Business Name</td>
                    <td style="padding: 0.75rem 0; font-weight: 500;">{{ $order->vendor->name }}</td>
                </tr>
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 0.75rem 0; color: #6b7280;">Owner</td>
                    <td style="padding: 0.75rem 0;">{{ $order->vendor->user->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="padding: 0.75rem 0; color: #6b7280;">Contact</td>
                    <td style="padding: 0.75rem 0;">{{ $order->vendor->contact_number ?? 'N/A' }}</td>
                </tr>
            </table>
            @else
            <p style="color: #6b7280;">Vendor information unavailable</p>
            @endif
        </div>
    </div>
</div>

<!-- Actions -->
@if((Auth::user()->isAdmin() || Auth::user()->isEnterpriseOwner()) && $order->status !== 'delivered' && $order->status !== 'cancelled')
<div class="card" style="margin-top: 1.5rem;">
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
<div class="card" style="margin-top: 1.5rem;">
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



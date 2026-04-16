@extends('layouts.dashboard')

@section('title', 'Promotions')
@section('page-title', 'Resort Promotions')

@section('header-actions')
@if(Auth::user()->isResortOwner())
<a href="{{ route('promotions.create') }}" class="btn btn-primary">
    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>
    New Promotion
</a>
@endif
@endsection

@section('content')
<!-- Filters -->
<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-body">
        <form method="GET" action="{{ route('promotions.index') }}" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: end;">
            <div class="form-group" style="margin: 0; min-width: 150px;">
                <label class="form-label">Status</label>
                <select name="status" class="form-input">
                    <option value="">All</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('promotions.index') }}" class="btn btn-secondary">Reset</a>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>All Promotions</h3>
        <span style="color: #7a4d63; font-size: 0.9rem;">{{ $promotions->total() }} promotions found</span>
    </div>
    <div class="card-body" style="padding: 0;">
        @if($promotions->count() > 0)
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Resort</th>
                        <th>Discount</th>
                        <th>Promo Code</th>
                        <th>Period</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($promotions as $promotion)
                    <tr>
                        <td>
                            <strong>{{ $promotion->title }}</strong>
                        </td>
                        <td>{{ $promotion->resort->name ?? 'N/A' }}</td>
                        <td>
                            @if($promotion->discount_percentage)
                            <span style="color: #be185d; font-weight: 600;">{{ $promotion->discount_percentage }}% OFF</span>
                            @elseif($promotion->discount_amount)
                            <span style="color: #be185d; font-weight: 600;">₱{{ number_format($promotion->discount_amount, 2) }} OFF</span>
                            @else
                            <span style="color: #6b7280;">-</span>
                            @endif
                        </td>
                        <td>
                            @if($promotion->promo_code)
                            <code style="background: #f3f4f6; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.85rem;">{{ $promotion->promo_code }}</code>
                            @else
                            <span style="color: #6b7280;">-</span>
                            @endif
                        </td>
                        <td style="font-size: 0.85rem;">
                            {{ $promotion->start_date ? $promotion->start_date->format('M d') : 'N/A' }} - 
                            {{ $promotion->end_date ? $promotion->end_date->format('M d, Y') : 'N/A' }}
                        </td>
                        <td>
                            @php
                                $now = now();
                                $isExpired = $promotion->end_date && $promotion->end_date < $now;
                                $isUpcoming = $promotion->start_date && $promotion->start_date > $now;
                                
                                if ($isExpired) {
                                    $statusStyle = 'background: #e5e7eb; color: #4b5563;';
                                    $statusText = 'Expired';
                                } elseif (!$promotion->is_active) {
                                    $statusStyle = 'background: #fee2e2; color: #991b1b;';
                                    $statusText = 'Inactive';
                                } elseif ($isUpcoming) {
                                    $statusStyle = 'background: #dbeafe; color: #1e40af;';
                                    $statusText = 'Upcoming';
                                } else {
                                    $statusStyle = 'background: #ffe3f1; color: #166534;';
                                    $statusText = 'Active';
                                }
                            @endphp
                            <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 500; {{ $statusStyle }}">
                                {{ $statusText }}
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="{{ route('promotions.edit', $promotion) }}" class="btn btn-secondary btn-sm">Edit</a>
                                <form action="{{ route('promotions.destroy', $promotion) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this promotion?');">
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
        <div style="padding: 1rem;">{{ $promotions->withQueryString()->links() }}</div>
        @else
        <div class="empty-state" style="padding: 3rem; text-align: center;">
            <svg width="48" height="48" fill="none" stroke="#9ca3af" viewBox="0 0 24 24" style="margin: 0 auto 1rem;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            <p style="color: #6b7280;">No promotions found</p>
            @if(Auth::user()->isResortOwner())
            <a href="{{ route('promotions.create') }}" class="btn btn-primary" style="margin-top: 1rem;">Create Your First Promotion</a>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection



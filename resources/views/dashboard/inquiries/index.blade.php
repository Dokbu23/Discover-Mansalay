@extends('layouts.dashboard')

@section('title', 'Inquiries')
@section('page-title', 'Inquiries')

@section('header-actions')
@if(Auth::user()->isTourist())
<a href="{{ route('inquiries.create') }}" class="btn btn-primary">
    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
    </svg>
    New Inquiry
</a>
@endif
@endsection

@section('content')
<!-- Filters -->
<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-body">
        <form method="GET" action="{{ route('inquiries.index') }}" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: end;">
            <div class="form-group" style="margin: 0; min-width: 150px;">
                <label class="form-label">Status</label>
                <select name="status" class="form-input">
                    <option value="">All Status</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="replied" {{ request('status') == 'replied' ? 'selected' : '' }}>Replied</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('inquiries.index') }}" class="btn btn-secondary">Reset</a>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>All Inquiries</h3>
        <span style="color: #7a4d63; font-size: 0.9rem;">{{ $inquiries->total() }} inquiries found</span>
    </div>
    <div class="card-body" style="padding: 0;">
        @if($inquiries->count() > 0)
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Subject</th>
                        @if(Auth::user()->isAdmin() || Auth::user()->isEnterpriseOwner())
                        <th>From</th>
                        @endif
                        @if(Auth::user()->isAdmin() || Auth::user()->isTourist())
                        <th>Vendor</th>
                        @endif
                        <th>Product</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inquiries as $inquiry)
                    <tr>
                        <td><strong>#{{ $inquiry->id }}</strong></td>
                        <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ $inquiry->subject }}
                        </td>
                        @if(Auth::user()->isAdmin() || Auth::user()->isEnterpriseOwner())
                        <td>{{ $inquiry->user->name ?? 'N/A' }}</td>
                        @endif
                        @if(Auth::user()->isAdmin() || Auth::user()->isTourist())
                        <td>{{ $inquiry->vendor->name ?? 'N/A' }}</td>
                        @endif
                        <td>{{ $inquiry->product->name ?? '-' }}</td>
                        <td>
                            @php
                                $statusColors = [
                                    'open' => 'background: #fef3c7; color: #92400e;',
                                    'replied' => 'background: #dbeafe; color: #1e40af;',
                                    'closed' => 'background: #e5e7eb; color: #4b5563;',
                                ];
                            @endphp
                            <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 500; {{ $statusColors[$inquiry->status] ?? '' }}">
                                {{ ucfirst($inquiry->status) }}
                            </span>
                        </td>
                        <td style="font-size: 0.85rem; color: #6b7280;">{{ $inquiry->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('inquiries.show', $inquiry) }}" class="btn btn-secondary btn-sm">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding: 1rem;">{{ $inquiries->withQueryString()->links() }}</div>
        @else
        <div class="empty-state" style="padding: 3rem; text-align: center;">
            <svg width="48" height="48" fill="none" stroke="#9ca3af" viewBox="0 0 24 24" style="margin: 0 auto 1rem;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <p style="color: #6b7280;">No inquiries found</p>
            @if(Auth::user()->isTourist())
            <a href="{{ route('inquiries.create') }}" class="btn btn-primary" style="margin-top: 1rem;">Send Your First Inquiry</a>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection


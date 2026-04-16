@extends('layouts.dashboard')

@section('title', 'Inquiry #' . $inquiry->id)
@section('page-title', 'Inquiry Details')

@section('header-actions')
<a href="{{ route('inquiries.index') }}" class="btn btn-secondary">
    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
    </svg>
    Back to Inquiries
</a>
@endsection

@section('content')
<div style="max-width: 800px;">
    <!-- Inquiry Info -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header">
            <h3>{{ $inquiry->subject }}</h3>
            @php
                $statusColors = [
                    'open' => 'background: #fef3c7; color: #92400e;',
                    'replied' => 'background: #dbeafe; color: #1e40af;',
                    'closed' => 'background: #e5e7eb; color: #4b5563;',
                ];
            @endphp
            <span style="padding: 0.35rem 1rem; border-radius: 20px; font-size: 0.85rem; font-weight: 500; {{ $statusColors[$inquiry->status] ?? '' }}">
                {{ ucfirst($inquiry->status) }}
            </span>
        </div>
        <div class="card-body">
            <div style="display: flex; gap: 2rem; flex-wrap: wrap; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid #e5e7eb;">
                <div>
                    <span style="color: #6b7280; font-size: 0.85rem;">From</span>
                    <p style="margin: 0.25rem 0 0; font-weight: 500;">{{ $inquiry->user->name ?? 'N/A' }}</p>
                    <p style="margin: 0; font-size: 0.85rem; color: #6b7280;">{{ $inquiry->user->email ?? '' }}</p>
                </div>
                <div>
                    <span style="color: #6b7280; font-size: 0.85rem;">To Vendor</span>
                    <p style="margin: 0.25rem 0 0; font-weight: 500;">{{ $inquiry->vendor->name ?? 'N/A' }}</p>
                </div>
                @if($inquiry->product)
                <div>
                    <span style="color: #6b7280; font-size: 0.85rem;">Regarding Product</span>
                    <p style="margin: 0.25rem 0 0; font-weight: 500;">{{ $inquiry->product->name }}</p>
                </div>
                @endif
                <div>
                    <span style="color: #6b7280; font-size: 0.85rem;">Date</span>
                    <p style="margin: 0.25rem 0 0; font-weight: 500;">{{ $inquiry->created_at->format('F d, Y h:i A') }}</p>
                </div>
            </div>

            <!-- Original Message -->
            <div style="background: #f9fafb; padding: 1.5rem; border-radius: 8px; margin-bottom: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                    <div style="width: 40px; height: 40px; background: #be185d; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                        {{ strtoupper(substr($inquiry->user->name ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <strong style="color: #1f2937;">{{ $inquiry->user->name ?? 'User' }}</strong>
                        <span style="color: #6b7280; font-size: 0.85rem; margin-left: 0.5rem;">{{ $inquiry->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                <p style="margin: 0; color: #374151; white-space: pre-wrap;">{{ $inquiry->message }}</p>
            </div>

            <!-- Reply -->
            @if($inquiry->reply)
            <div style="background: #fff1f7; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #be185d;">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                    <div style="width: 40px; height: 40px; background: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #be185d; font-weight: 600;">
                        {{ strtoupper(substr($inquiry->vendor->name ?? 'V', 0, 1)) }}
                    </div>
                    <div>
                        <strong style="color: #1f2937;">{{ $inquiry->vendor->name ?? 'Vendor' }}</strong>
                        <span style="color: #6b7280; font-size: 0.85rem; margin-left: 0.5rem;">
                            @if($inquiry->replied_at)
                            {{ $inquiry->replied_at->diffForHumans() }}
                            @endif
                        </span>
                    </div>
                </div>
                <p style="margin: 0; color: #374151; white-space: pre-wrap;">{{ $inquiry->reply }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Reply Form for Enterprise Owner -->
    @if(Auth::user()->isEnterpriseOwner() && $inquiry->status !== 'closed')
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header">
            <h3>{{ $inquiry->reply ? 'Update Reply' : 'Send Reply' }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('inquiries.reply', $inquiry) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label class="form-label">Your Reply <span style="color: #dc2626;">*</span></label>
                    <textarea name="reply" class="form-input" rows="5" required placeholder="Type your reply here...">{{ old('reply', $inquiry->reply) }}</textarea>
                    @error('reply')
                    <span style="color: #dc2626; font-size: 0.85rem;">{{ $message }}</span>
                    @enderror
                </div>
                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                        </svg>
                        Send Reply
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Actions -->
    <div class="card">
        <div class="card-header">
            <h3>Actions</h3>
        </div>
        <div class="card-body">
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                @if((Auth::user()->isEnterpriseOwner() || Auth::user()->isAdmin()) && $inquiry->status !== 'closed')
                <form action="{{ route('inquiries.close', $inquiry) }}" method="POST" onsubmit="return confirm('Are you sure you want to close this inquiry?');">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-secondary">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Mark as Closed
                    </button>
                </form>
                @endif

                @if(Auth::user()->isAdmin())
                <form action="{{ route('inquiries.destroy', $inquiry) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this inquiry?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete Inquiry
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection


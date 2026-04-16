@extends('layouts.dashboard')

@section('title', 'Support Ticket #' . $supportRequest->id)
@section('page-title', 'Ticket #' . $supportRequest->id)

@section('content')
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
    <!-- Main Content -->
    <div>
        <div class="card">
            <div class="card-header">
                <h3>{{ $supportRequest->subject }}</h3>
                @php
                    $statusColors = [
                        'open' => 'background: #fef3c7; color: #92400e;',
                        'in_progress' => 'background: #dbeafe; color: #1d4ed8;',
                        'resolved' => 'background: #fde7f3; color: #9d174d;',
                        'closed' => 'background: #e5e7eb; color: #374151;',
                    ];
                @endphp
                <span class="status-badge" style="{{ $statusColors[$supportRequest->status] ?? '' }}">
                    {{ ucfirst(str_replace('_', ' ', $supportRequest->status)) }}
                </span>
            </div>
            <div class="card-body">
                <div style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <div style="width: 45px; height: 45px; background: #be185d; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                            {{ strtoupper(substr($supportRequest->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <strong>{{ $supportRequest->user->name }}</strong>
                            <p style="margin: 0; font-size: 0.85rem; color: #7a4d63;">{{ $supportRequest->user->email }}</p>
                        </div>
                        <span style="margin-left: auto; font-size: 0.85rem; color: #7a4d63;">
                            {{ $supportRequest->created_at->format('M d, Y H:i') }}
                        </span>
                    </div>
                    <div style="background: #fff7fb; padding: 1rem; border-radius: 10px;">
                        <p style="margin: 0; white-space: pre-wrap;">{{ $supportRequest->message }}</p>
                    </div>
                </div>

                @if($supportRequest->admin_response)
                <div style="margin-top: 1.5rem;">
                    <h4 style="color: #be185d; margin-bottom: 1rem;">Admin Response</h4>
                    <div style="display: flex; align-items: flex-start; gap: 1rem;">
                        <div style="width: 45px; height: 45px; background: #fff; color: #be185d; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div style="flex: 1; background: #fff1f7; padding: 1rem; border-radius: 10px; border-left: 3px solid #db2777;">
                            <p style="margin: 0; white-space: pre-wrap;">{{ $supportRequest->admin_response }}</p>
                            @if($supportRequest->resolved_at)
                            <p style="margin: 0.5rem 0 0 0; font-size: 0.8rem; color: #db2777;">
                                Resolved on {{ $supportRequest->resolved_at->format('M d, Y H:i') }}
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                @if(Auth::user()->isAdmin() && $supportRequest->status !== 'closed')
                <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
                    <h4 style="color: #be185d; margin-bottom: 1rem;">Admin Response</h4>
                    <form method="POST" action="{{ route('support.update', $supportRequest) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label class="form-label">Update Status</label>
                            <select name="status" class="form-input form-select">
                                <option value="open" {{ $supportRequest->status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ $supportRequest->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ $supportRequest->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ $supportRequest->status == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Response Message</label>
                            <textarea name="admin_response" class="form-input form-textarea" rows="4" placeholder="Type your response to the user...">{{ $supportRequest->admin_response }}</textarea>
                        </div>

                        <div style="display: flex; gap: 1rem;">
                            <button type="submit" class="btn btn-primary">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Update Ticket
                            </button>
                            <a href="{{ route('support.index') }}" class="btn btn-secondary">Back to List</a>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div>
        <div class="card">
            <div class="card-header">
                <h3>Ticket Details</h3>
            </div>
            <div class="card-body">
                <div style="margin-bottom: 1rem;">
                    <label style="font-size: 0.75rem; text-transform: uppercase; color: #7a4d63; letter-spacing: 0.5px;">Ticket ID</label>
                    <p style="margin: 0.25rem 0 0 0; font-weight: 600;">#{{ $supportRequest->id }}</p>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="font-size: 0.75rem; text-transform: uppercase; color: #7a4d63; letter-spacing: 0.5px;">Category</label>
                    <p style="margin: 0.25rem 0 0 0;">
                        <span class="status-badge" style="background: #e0e7ff; color: #4f46e5;">
                            {{ ucfirst($supportRequest->category) }}
                        </span>
                    </p>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="font-size: 0.75rem; text-transform: uppercase; color: #7a4d63; letter-spacing: 0.5px;">Priority</label>
                    <p style="margin: 0.25rem 0 0 0;">
                        @php
                            $priorityColors = [
                                'low' => 'background: #fde7f3; color: #9d174d;',
                                'medium' => 'background: #fff3cd; color: #856404;',
                                'high' => 'background: #ffe4e6; color: #be123c;',
                                'urgent' => 'background: #fee2e2; color: #dc2626;',
                            ];
                        @endphp
                        <span class="status-badge" style="{{ $priorityColors[$supportRequest->priority] ?? '' }}">
                            {{ ucfirst($supportRequest->priority) }}
                        </span>
                    </p>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="font-size: 0.75rem; text-transform: uppercase; color: #7a4d63; letter-spacing: 0.5px;">Submitted</label>
                    <p style="margin: 0.25rem 0 0 0;">{{ $supportRequest->created_at->format('M d, Y') }}<br><small style="color: #7a4d63;">{{ $supportRequest->created_at->format('H:i A') }}</small></p>
                </div>

                @if($supportRequest->resolved_at)
                <div style="margin-bottom: 1rem;">
                    <label style="font-size: 0.75rem; text-transform: uppercase; color: #7a4d63; letter-spacing: 0.5px;">Resolved</label>
                    <p style="margin: 0.25rem 0 0 0;">{{ $supportRequest->resolved_at->format('M d, Y') }}<br><small style="color: #7a4d63;">{{ $supportRequest->resolved_at->format('H:i A') }}</small></p>
                </div>
                @endif

                <div>
                    <label style="font-size: 0.75rem; text-transform: uppercase; color: #7a4d63; letter-spacing: 0.5px;">User Role</label>
                    <p style="margin: 0.25rem 0 0 0;">{{ ucfirst(str_replace('_', ' ', $supportRequest->user->role ?? 'tourist')) }}</p>
                </div>
            </div>
        </div>

        @if(!Auth::user()->isAdmin())
        <div class="card" style="margin-top: 1rem;">
            <div class="card-body" style="text-align: center;">
                <a href="{{ route('support.index') }}" class="btn btn-secondary" style="width: 100%;">
                    Back to My Tickets
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection



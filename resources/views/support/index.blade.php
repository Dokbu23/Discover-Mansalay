@extends('layouts.dashboard')

@section('title', 'Support Tickets')
@section('page-title', 'Support Tickets')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>All Support Requests</h3>
        <div style="display: flex; gap: 1rem;">
            <form method="GET" action="{{ route('support.index') }}" style="display: flex; gap: 0.5rem;">
                <select name="status" class="form-input" style="width: auto; padding: 0.5rem;" onchange="this.form.submit()">
                    <option value="all">All Status</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
                <select name="priority" class="form-input" style="width: auto; padding: 0.5rem;" onchange="this.form.submit()">
                    <option value="all">All Priority</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </form>
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        @if($supportRequests->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Subject</th>
                        <th>Category</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($supportRequests as $request)
                    <tr>
                        <td>#{{ $request->id }}</td>
                        <td>
                            <div>
                                <strong>{{ $request->user->name }}</strong>
                                <br><small style="color: #7a4d63;">{{ $request->user->email }}</small>
                            </div>
                        </td>
                        <td>{{ Str::limit($request->subject, 40) }}</td>
                        <td>
                            <span class="status-badge" style="background: #e0e7ff; color: #4f46e5;">
                                {{ ucfirst($request->category) }}
                            </span>
                        </td>
                        <td>
                            @php
                                $priorityColors = [
                                    'low' => 'background: #fde7f3; color: #9d174d;',
                                    'medium' => 'background: #fff3cd; color: #856404;',
                                    'high' => 'background: #ffe4e6; color: #be123c;',
                                    'urgent' => 'background: #fee2e2; color: #dc2626;',
                                ];
                            @endphp
                            <span class="status-badge" style="{{ $priorityColors[$request->priority] ?? '' }}">
                                {{ ucfirst($request->priority) }}
                            </span>
                        </td>
                        <td>
                            @php
                                $statusColors = [
                                    'open' => 'background: #fef3c7; color: #92400e;',
                                    'in_progress' => 'background: #dbeafe; color: #1d4ed8;',
                                    'resolved' => 'background: #fde7f3; color: #9d174d;',
                                    'closed' => 'background: #e5e7eb; color: #374151;',
                                ];
                            @endphp
                            <span class="status-badge" style="{{ $statusColors[$request->status] ?? '' }}">
                                {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                            </span>
                        </td>
                        <td>{{ $request->created_at->format('M d, Y H:i') }}</td>
                        <td>
                            <a href="{{ route('support.show', $request) }}" class="btn btn-secondary btn-sm">
                                View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div style="padding: 1rem;">
                {{ $supportRequests->withQueryString()->links() }}
            </div>
        @else
            <div class="empty-state">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p>No support requests found.</p>
            </div>
        @endif
    </div>
</div>

<!-- Stats Cards -->
<div class="stats-grid" style="margin-top: 2rem;">
    <div class="stat-card">
        <div class="stat-icon" style="background: #fef3c7; color: #92400e;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ \App\Models\SupportRequest::where('status', 'open')->count() }}</h3>
            <p>Open Tickets</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: #dbeafe; color: #1d4ed8;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ \App\Models\SupportRequest::where('status', 'in_progress')->count() }}</h3>
            <p>In Progress</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: #fde7f3; color: #9d174d;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ \App\Models\SupportRequest::where('status', 'resolved')->count() }}</h3>
            <p>Resolved</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: #fee2e2; color: #dc2626;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ \App\Models\SupportRequest::where('priority', 'urgent')->where('status', '!=', 'closed')->count() }}</h3>
            <p>Urgent</p>
        </div>
    </div>
</div>
@endsection


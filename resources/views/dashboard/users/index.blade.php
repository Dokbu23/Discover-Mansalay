@extends('layouts.dashboard')

@section('title', 'User Management')
@section('page-title', 'User Management')

@section('header-actions')
<a href="{{ route('users.create') }}" class="btn btn-primary">
    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>
    Add User
</a>
@endsection

@section('content')
<!-- Filters -->
<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-body">
        <form id="user-filter-form" method="GET" action="{{ route('users.index') }}" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: end;">
            <div class="form-group" style="margin: 0; flex: 1; min-width: 200px;">
                <label class="form-label">Search</label>
                <input id="user-search-input" type="text" name="search" class="form-input" placeholder="Search by name or email..." value="{{ request('search') }}">
            </div>
            <div class="form-group" style="margin: 0; min-width: 150px;">
                <label class="form-label">Role</label>
                <select name="role" class="form-input" onchange="this.form.submit()">
                    <option value="">All Roles</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="resort_owner" {{ request('role') == 'resort_owner' ? 'selected' : '' }}>Resort Owner</option>
                    <option value="enterprise_owner" {{ request('role') == 'enterprise_owner' ? 'selected' : '' }}>Enterprise Owner</option>
                    <option value="tourist" {{ request('role') == 'tourist' ? 'selected' : '' }}>Tourist</option>
                </select>
            </div>
            <div class="form-group" style="margin: 0; min-width: 120px;">
                <label class="form-label">Status</label>
                <select name="status" class="form-input" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="form-group" style="margin: 0; min-width: 120px;">
                <label class="form-label">Approval</label>
                <select name="approval" class="form-input" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option value="pending" {{ request('approval') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('approval') == 'approved' ? 'selected' : '' }}>Approved</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Reset</a>
        </form>
    </div>
</div>

<!-- Stats -->
<div class="stats-grid" style="margin-bottom: 1.5rem;">
    <div class="stat-card">
        <div class="stat-icon green">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ \App\Models\User::count() }}</h3>
            <p>Total Users</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #dc2626, #ef4444);">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ \App\Models\User::where('role', 'admin')->count() }}</h3>
            <p>Admins</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ \App\Models\User::where('role', 'resort_owner')->count() }}</h3>
            <p>Resort Owners</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon gold">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ \App\Models\User::whereIn('role', ['tourist', null])->count() }}</h3>
            <p>Tourists</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ \App\Models\User::where('is_approved', false)->count() }}</h3>
            <p>Pending Approval</p>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-header">
        <h3>All Users</h3>
        <span style="color: #7a4d63; font-size: 0.9rem;">{{ $users->total() }} users found</span>
    </div>
    <div class="card-body" style="padding: 0;">
        @if($users->count() > 0)
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Approval</th>
                            <th>Payment</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #be185d, #db2777); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <strong>{{ $user->name }}</strong>
                                        @if($user->id === Auth::id())
                                            <span style="font-size: 0.7rem; background: #fef3c7; color: #92400e; padding: 0.15rem 0.4rem; border-radius: 4px; margin-left: 0.5rem;">You</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @php
                                    $roleKey = str_replace('_', '-', $user->role ?? 'tourist');
                                    $roleClass = 'role-badge role-' . $roleKey;
                                @endphp
                                <span class="{{ $roleClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $user->role ?? 'tourist')) }}
                                </span>
                            </td>
                            <td>
                                @if($user->is_active)
                                    <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 500; background: #ffe3f1; color: #db2777;">Active</span>
                                @else
                                    <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 500; background: #fee2e2; color: #dc2626;">Inactive</span>
                                @endif
                            </td>
                            <td>
                                @if($user->is_approved)
                                    <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 500; background: #ffe3f1; color: #db2777;">Approved</span>
                                @else
                                    <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 500; background: #fef3c7; color: #92400e;">Pending</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $isVendorRole = in_array($user->role, ['resort_owner', 'enterprise_owner'], true);
                                @endphp
                                @if(!$isVendorRole)
                                    <span style="color: #9ca3af; font-size: 0.8rem;">-</span>
                                @elseif($user->hasVerifiedVendorPayment())
                                    <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 500; background: #dcfce7; color: #15803d;">Verified</span>
                                @elseif($user->hasSubmittedVendorPayment())
                                    <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 500; background: #dbeafe; color: #2563eb;">Submitted</span>
                                @else
                                    <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 500; background: #f3f4f6; color: #6b7280;">Not Submitted</span>
                                @endif
                            </td>
                            <td style="color: #6b7280; font-size: 0.85rem;">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td>
                                <details class="action-dropdown">
                                    <summary>Actions</summary>
                                    <div class="action-menu">
                                        @if(!$user->is_approved)
                                            @if($isVendorRole)
                                                @if($user->hasSubmittedVendorPayment())
                                                    <a href="{{ route('users.payment.receipt.view', $user) }}" class="btn btn-secondary btn-sm" target="_blank" rel="noopener">View Payment</a>
                                                    <a href="{{ route('users.payment.receipt', $user) }}" class="btn btn-secondary btn-sm">Download</a>
                                                @endif

                                                @if($user->hasSubmittedVendorPayment() && !$user->hasVerifiedVendorPayment())
                                                    <form action="{{ route('users.payment.verify', $user) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-primary btn-sm" style="background: #0ea5e9;">Verify Payment</button>
                                                    </form>
                                                @endif

                                                @if($user->hasVerifiedVendorPayment())
                                                    <form action="{{ route('users.approve', $user) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-primary btn-sm" style="background: #db2777;">Approve</button>
                                                    </form>
                                                @endif
                                            @else
                                                <form action="{{ route('users.approve', $user) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-primary btn-sm" style="background: #db2777;">Approve</button>
                                                </form>
                                            @endif

                                            <form action="{{ route('users.reject', $user) }}" method="POST" onsubmit="return confirm('Reject this registration? The user will be deleted.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                            </form>
                                        @else
                                            <a href="{{ route('users.show', $user) }}" class="btn btn-secondary btn-sm">View</a>
                                            <a href="{{ route('users.edit', $user) }}" class="btn btn-secondary btn-sm">Edit</a>
                                            @if($user->id !== Auth::id())
                                                @php
                                                    $toggleMessage = 'Are you sure you want to ' . ($user->is_active ? 'deactivate' : 'activate') . ' this user?';
                                                @endphp
                                                <form action="{{ route('users.destroy', $user) }}" method="POST" data-confirm="{{ $toggleMessage }}" onsubmit="return confirm(this.dataset.confirm);">
                                                    @csrf
                                                    @method('DELETE')
                                                    @if($user->is_active)
                                                        <button type="submit" class="btn btn-danger btn-sm">Deactivate</button>
                                                    @else
                                                        <button type="submit" class="btn btn-primary btn-sm">Activate</button>
                                                    @endif
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </details>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
            <div style="padding: 1rem; border-top: 1px solid #e5e7eb;">
                {{ $users->withQueryString()->links() }}
            </div>
            @endif
        @else
            <div class="empty-state">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <p>No users found</p>
            </div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
    .role-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-block;
    }

    .role-admin { background: #fee2e2; color: #dc2626; }
    .role-resort-owner { background: #dbeafe; color: #2563eb; }
    .role-enterprise-owner { background: #f3e8ff; color: #7c3aed; }
    .role-tourist { background: #ffe3f1; color: #db2777; }
    .role-default { background: #f3f4f6; color: #6b7280; }

    .action-dropdown {
        position: relative;
    }

    .action-dropdown > summary {
        list-style: none;
        cursor: pointer;
        padding: 0.4rem 0.75rem;
        border-radius: 8px;
        background: #f0f4f3;
        color: #be185d;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .action-dropdown > summary::-webkit-details-marker {
        display: none;
    }

    .action-menu {
        position: absolute;
        right: 0;
        margin-top: 0.5rem;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 0.75rem;
        display: grid;
        gap: 0.5rem;
        min-width: 180px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        z-index: 20;
    }

    .action-dropdown[open] .action-menu {
        animation: fadeIn 0.15s ease;
    }

    .action-menu .btn {
        width: 100%;
        justify-content: center;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-4px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection

@section('scripts')
<script>
    (function () {
        var searchInput = document.getElementById('user-search-input');
        var filterForm = document.getElementById('user-filter-form');

        if (!searchInput || !filterForm) {
            return;
        }

        var debounceTimer;

        searchInput.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function () {
                filterForm.submit();
            }, 450);
        });
    })();
</script>
@endsection



@extends('layouts.dashboard')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Edit User: {{ $user->name }}</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label class="form-label">Full Name *</label>
                <input type="text" name="name" class="form-input @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <p style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Email Address *</label>
                <input type="email" name="email" class="form-input @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <p style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Role *</label>
                <select name="role" class="form-input @error('role') is-invalid @enderror" required>
                    <option value="tourist" {{ old('role', $user->role) == 'tourist' ? 'selected' : '' }}>Tourist</option>
                    <option value="resort_owner" {{ old('role', $user->role) == 'resort_owner' ? 'selected' : '' }}>Resort Owner</option>
                    <option value="enterprise_owner" {{ old('role', $user->role) == 'enterprise_owner' ? 'selected' : '' }}>Enterprise Owner (Vendor)</option>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role')
                    <p style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
                @if($user->id === Auth::id())
                    <p style="color: #dc2626; font-size: 0.8rem; margin-top: 0.5rem;">
                        <strong>Warning:</strong> Changing your own role may affect your access to the system.
                    </p>
                @endif
            </div>

            <div style="background: #f9fafb; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                <h4 style="font-size: 0.9rem; color: #374151; margin-bottom: 0.75rem;">Change Password (Optional)</h4>
                <p style="font-size: 0.8rem; color: #7a4d63; margin-bottom: 1rem;">Leave blank to keep current password</p>
                
                <div class="form-row">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-input @error('password') is-invalid @enderror">
                        @error('password')
                            <p style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-input">
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">Update User</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<!-- User Activity Info -->
<div class="card" style="margin-top: 1.5rem;">
    <div class="card-header">
        <h3>Account Information</h3>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
            <div>
                <p style="font-size: 0.85rem; color: #7a4d63;">Account Created</p>
                <p style="font-weight: 600;">{{ $user->created_at->format('F d, Y') }}</p>
                <p style="font-size: 0.8rem; color: #9ca3af;">{{ $user->created_at->diffForHumans() }}</p>
            </div>
            <div>
                <p style="font-size: 0.85rem; color: #7a4d63;">Last Updated</p>
                <p style="font-weight: 600;">{{ $user->updated_at->format('F d, Y') }}</p>
                <p style="font-size: 0.8rem; color: #9ca3af;">{{ $user->updated_at->diffForHumans() }}</p>
            </div>
            <div>
                <p style="font-size: 0.85rem; color: #7a4d63;">User ID</p>
                <p style="font-weight: 600;">#{{ $user->id }}</p>
            </div>
        </div>
    </div>
</div>
@endsection


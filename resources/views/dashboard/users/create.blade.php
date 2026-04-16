@extends('layouts.dashboard')

@section('title', 'Add User')
@section('page-title', 'Add User')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Create New User</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Full Name *</label>
                <input type="text" name="name" class="form-input @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                @error('name')
                    <p style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Email Address *</label>
                <input type="email" name="email" class="form-input @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                @error('email')
                    <p style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Role *</label>
                <select name="role" class="form-input @error('role') is-invalid @enderror" required>
                    <option value="">Select Role</option>
                    <option value="tourist" {{ old('role') == 'tourist' ? 'selected' : '' }}>Tourist</option>
                    <option value="resort_owner" {{ old('role') == 'resort_owner' ? 'selected' : '' }}>Resort Owner</option>
                    <option value="enterprise_owner" {{ old('role') == 'enterprise_owner' ? 'selected' : '' }}>Enterprise Owner (Vendor)</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role')
                    <p style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
                <p style="color: #7a4d63; font-size: 0.8rem; margin-top: 0.5rem;">
                    <strong>Tourist:</strong> Can browse and book resorts<br>
                    <strong>Resort Owner:</strong> Can manage their own resorts and rooms<br>
                    <strong>Enterprise Owner:</strong> Can manage their vendor profile and products<br>
                    <strong>Admin:</strong> Full system access
                </p>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Password *</label>
                    <input type="password" name="password" class="form-input @error('password') is-invalid @enderror" required>
                    @error('password')
                        <p style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Confirm Password *</label>
                    <input type="password" name="password_confirmation" class="form-input" required>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">Create User</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection


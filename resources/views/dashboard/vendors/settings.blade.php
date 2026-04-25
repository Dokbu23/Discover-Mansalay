@extends('layouts.dashboard')

@section('title', 'Vendor Settings')
@section('page-title', 'Vendor Settings')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('vendors.settings.save') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Business Name *</label>
                    <input
                        type="text"
                        name="name"
                        class="form-input"
                        value="{{ old('name', optional($vendor)->name) }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label class="form-label">Business Type *</label>
                    <select name="type" class="form-input form-select" required>
                        <option value="">Select Type</option>
                        <option value="awati" {{ old('type', optional($vendor)->type) == 'awati' ? 'selected' : '' }}>Awati</option>
                        <option value="pasalubong_center" {{ old('type', optional($vendor)->type) == 'pasalubong_center' ? 'selected' : '' }}>Pasalubong Center</option>
                        <option value="other" {{ old('type', optional($vendor)->type) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Contact Number</label>
                    <input
                        type="text"
                        name="contact_number"
                        class="form-input"
                        value="{{ old('contact_number', optional($vendor)->contact_number) }}"
                    >
                </div>

                <div class="form-group">
                    <label class="form-label">Address</label>
                    <input
                        type="text"
                        name="address"
                        class="form-input"
                        value="{{ old('address', optional($vendor)->address) }}"
                    >
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-input form-textarea">{{ old('description', optional($vendor)->description) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Logo</label>
                @if(optional($vendor)->logo)
                    <div style="margin-bottom: 0.5rem;">
                        <img src="{{ asset('storage/' . $vendor->logo) }}" alt="{{ $vendor->name }}" style="max-width: 150px; border-radius: 8px;">
                    </div>
                @endif
                <input type="file" name="logo" class="form-input" accept="image/*">
            </div>

            <div class="form-group">
                <label class="form-checkbox">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', optional($vendor)->is_active ?? true) ? 'checked' : '' }}>
                    <span>Active</span>
                </label>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">Save Settings</button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
            </div>
        </form>
    </div>
</div>
@endsection

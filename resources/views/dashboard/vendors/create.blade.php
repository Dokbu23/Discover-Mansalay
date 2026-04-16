@extends('layouts.dashboard')

@section('title', 'Add Vendor')
@section('page-title', 'Add Vendor')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('vendors.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Type *</label>
                    <select name="type" class="form-input form-select" required>
                        <option value="">Select Type</option>
                        <option value="awati" {{ old('type') == 'awati' ? 'selected' : '' }}>Awati</option>
                        <option value="pasalubong_center" {{ old('type') == 'pasalubong_center' ? 'selected' : '' }}>Pasalubong Center</option>
                        <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Contact Number</label>
                    <input type="text" name="contact_number" class="form-input" value="{{ old('contact_number') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-input" value="{{ old('address') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-input form-textarea">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Logo</label>
                <input type="file" name="logo" class="form-input" accept="image/*">
            </div>

            <div class="form-group">
                <label class="form-checkbox">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <span>Active</span>
                </label>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">Save Vendor</button>
                <a href="{{ route('vendors.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
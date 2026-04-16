@extends('layouts.dashboard')

@section('title', 'Edit Resort')
@section('page-title', 'Edit Resort')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('resorts.update', $resort) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $resort->name) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Contact Number</label>
                    <input type="text" name="contact_number" class="form-input" value="{{ old('contact_number', $resort->contact_number) }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email', $resort->email) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-input" value="{{ old('address', $resort->address) }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-input form-textarea">{{ old('description', $resort->description) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Cover Image</label>
                @if($resort->cover_image)
                    <div style="margin-bottom: 0.5rem;">
                        <img src="{{ asset('storage/' . $resort->cover_image) }}" alt="{{ $resort->name }}" style="max-width: 200px; border-radius: 8px;">
                    </div>
                @endif
                <input type="file" name="cover_image" class="form-input" accept="image/*">
            </div>

            <div class="form-group">
                <label class="form-checkbox">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $resort->is_active) ? 'checked' : '' }}>
                    <span>Active</span>
                </label>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">Update Resort</button>
                <a href="{{ route('resorts.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
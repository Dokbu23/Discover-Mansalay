@extends('layouts.dashboard')

@section('title', 'Edit Heritage Site')
@section('page-title', 'Edit Heritage Site')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('heritage.update', $heritage) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $heritage->name) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Location</label>
                    <input type="text" name="location" class="form-input" value="{{ old('location', $heritage->location) }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-input form-textarea">{{ old('description', $heritage->description) }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Entrance Fee (₱)</label>
                    <input type="number" name="entrance_fee" class="form-input" value="{{ old('entrance_fee', $heritage->entrance_fee) }}" step="0.01" min="0">
                </div>

                <div class="form-group">
                    <label class="form-label">Image</label>
                    @if($heritage->image)
                        <div style="margin-bottom: 0.5rem;">
                            <img src="{{ asset('storage/' . $heritage->image) }}" alt="{{ $heritage->name }}" style="max-width: 200px; border-radius: 8px;">
                        </div>
                    @endif
                    <input type="file" name="image" class="form-input" accept="image/*">
                    <small style="color: #7a4d63;">Leave empty to keep current image</small>
                </div>
            </div>

            <div class="form-group">
                <label class="form-checkbox">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $heritage->is_active) ? 'checked' : '' }}>
                    <span>Active</span>
                </label>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">Update Heritage Site</button>
                <a href="{{ route('heritage.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

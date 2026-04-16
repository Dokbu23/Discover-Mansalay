@extends('layouts.dashboard')

@section('title', 'Add Heritage Site')
@section('page-title', 'Add Heritage Site')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('heritage.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Location</label>
                    <input type="text" name="location" class="form-input" value="{{ old('location') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-input form-textarea">{{ old('description') }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Entrance Fee (₱)</label>
                    <input type="number" name="entrance_fee" class="form-input" value="{{ old('entrance_fee') }}" step="0.01" min="0">
                </div>

                <div class="form-group">
                    <label class="form-label">Image</label>
                    <input type="file" name="image" class="form-input" accept="image/*">
                </div>
            </div>

            <div class="form-group">
                <label class="form-checkbox">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <span>Active</span>
                </label>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">Save Heritage Site</button>
                <a href="{{ route('heritage.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
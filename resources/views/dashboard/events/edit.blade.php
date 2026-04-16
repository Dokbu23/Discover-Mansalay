@extends('layouts.dashboard')

@section('title', 'Edit Event')
@section('page-title', 'Edit Event')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('events.update', $event) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $event->name) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Location</label>
                    <input type="text" name="location" class="form-input" value="{{ old('location', $event->location) }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-input form-textarea">{{ old('description', $event->description) }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Start Date *</label>
                    <input type="date" name="start_date" class="form-input" value="{{ old('start_date', $event->start_date ? \Carbon\Carbon::parse($event->start_date)->format('Y-m-d') : '') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-input" value="{{ old('end_date', $event->end_date ? \Carbon\Carbon::parse($event->end_date)->format('Y-m-d') : '') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Image</label>
                @if($event->image)
                    <div style="margin-bottom: 0.5rem;">
                        <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->name }}" style="max-width: 200px; border-radius: 8px;">
                    </div>
                @endif
                <input type="file" name="image" class="form-input" accept="image/*">
            </div>

            <div class="form-group">
                <label class="form-checkbox">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $event->is_featured) ? 'checked' : '' }}>
                    <span>Featured Event</span>
                </label>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">Update Event</button>
                <a href="{{ route('events.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
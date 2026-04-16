@extends('layouts.dashboard')

@section('title', 'Add Room')
@section('page-title', 'Add Room')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('rooms.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Resort *</label>
                    <select name="resort_id" class="form-input form-select" required>
                        <option value="">Select Resort</option>
                        @foreach($resorts as $resort)
                        <option value="{{ $resort->id }}" {{ old('resort_id') == $resort->id ? 'selected' : '' }}>{{ $resort->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-input form-textarea">{{ old('description') }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Room Type *</label>
                    <select name="room_type" class="form-input form-select" required>
                        <option value="standard" {{ old('room_type') == 'standard' ? 'selected' : '' }}>Standard</option>
                        <option value="deluxe" {{ old('room_type') == 'deluxe' ? 'selected' : '' }}>Deluxe</option>
                        <option value="suite" {{ old('room_type') == 'suite' ? 'selected' : '' }}>Suite</option>
                        <option value="villa" {{ old('room_type') == 'villa' ? 'selected' : '' }}>Villa</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Capacity *</label>
                    <input type="number" name="capacity" class="form-input" value="{{ old('capacity', 2) }}" min="1" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Price per Night *</label>
                    <input type="number" name="price_per_night" class="form-input" value="{{ old('price_per_night') }}" step="0.01" min="0" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Image</label>
                    <input type="file" name="image" class="form-input" accept="image/*">
                </div>
            </div>

            <div class="form-group">
                <label class="form-checkbox">
                    <input type="checkbox" name="is_available" value="1" {{ old('is_available', true) ? 'checked' : '' }}>
                    <span>Available</span>
                </label>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">Save Room</button>
                <a href="{{ route('rooms.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
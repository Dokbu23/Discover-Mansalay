@extends('layouts.dashboard')

@section('title', 'Edit Room')
@section('page-title', 'Edit Room')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('rooms.update', $room) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $room->name) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Resort *</label>
                    <select name="resort_id" class="form-input form-select" required>
                        @foreach($resorts as $resort)
                        <option value="{{ $resort->id }}" {{ old('resort_id', $room->resort_id) == $resort->id ? 'selected' : '' }}>{{ $resort->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-input form-textarea">{{ old('description', $room->description) }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Room Type *</label>
                    <select name="room_type" class="form-input form-select" required>
                        <option value="standard" {{ old('room_type', $room->room_type) == 'standard' ? 'selected' : '' }}>Standard</option>
                        <option value="deluxe" {{ old('room_type', $room->room_type) == 'deluxe' ? 'selected' : '' }}>Deluxe</option>
                        <option value="suite" {{ old('room_type', $room->room_type) == 'suite' ? 'selected' : '' }}>Suite</option>
                        <option value="villa" {{ old('room_type', $room->room_type) == 'villa' ? 'selected' : '' }}>Villa</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Capacity *</label>
                    <input type="number" name="capacity" class="form-input" value="{{ old('capacity', $room->capacity) }}" min="1" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Price per Night *</label>
                    <input type="number" name="price_per_night" class="form-input" value="{{ old('price_per_night', $room->price_per_night) }}" step="0.01" min="0" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Image</label>
                    @if($room->images && count($room->images) > 0)
                        <div style="margin-bottom: 0.5rem;">
                            <img src="{{ asset('storage/' . $room->images[0]) }}" alt="{{ $room->name }}" style="max-width: 150px; border-radius: 8px;">
                        </div>
                    @endif
                    <input type="file" name="image" class="form-input" accept="image/*">
                </div>
            </div>

            <div class="form-group">
                <label class="form-checkbox">
                    <input type="checkbox" name="is_available" value="1" {{ old('is_available', $room->is_available) ? 'checked' : '' }}>
                    <span>Available</span>
                </label>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">Update Room</button>
                <a href="{{ route('rooms.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
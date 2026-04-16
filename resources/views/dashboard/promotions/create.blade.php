@extends('layouts.dashboard')

@section('title', 'Create Promotion')
@section('page-title', 'Create New Promotion')

@section('header-actions')
<a href="{{ route('promotions.index') }}" class="btn btn-secondary">
    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
    </svg>
    Back to Promotions
</a>
@endsection

@section('content')
<div class="card" style="max-width: 700px;">
    <div class="card-header">
        <h3>Promotion Details</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('promotions.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Select Resort <span style="color: #dc2626;">*</span></label>
                <select name="resort_id" class="form-input" required>
                    <option value="">Choose a resort...</option>
                    @foreach($resorts as $resort)
                    <option value="{{ $resort->id }}" {{ old('resort_id') == $resort->id ? 'selected' : '' }}>
                        {{ $resort->name }}
                    </option>
                    @endforeach
                </select>
                @error('resort_id')
                <span style="color: #dc2626; font-size: 0.85rem;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Promotion Title <span style="color: #dc2626;">*</span></label>
                <input type="text" name="title" class="form-input" value="{{ old('title') }}" required placeholder="e.g., Summer Sale - 20% Off All Rooms">
                @error('title')
                <span style="color: #dc2626; font-size: 0.85rem;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-input" rows="3" placeholder="Describe the promotion details...">{{ old('description') }}</textarea>
                @error('description')
                <span style="color: #dc2626; font-size: 0.85rem;">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Discount Percentage</label>
                    <div style="position: relative;">
                        <input type="number" name="discount_percentage" class="form-input" value="{{ old('discount_percentage') }}" min="0" max="100" step="0.01" placeholder="e.g., 20">
                        <span style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: #6b7280;">%</span>
                    </div>
                    @error('discount_percentage')
                    <span style="color: #dc2626; font-size: 0.85rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">OR Fixed Discount Amount</label>
                    <div style="position: relative;">
                        <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #6b7280;">₱</span>
                        <input type="number" name="discount_amount" class="form-input" style="padding-left: 2rem;" value="{{ old('discount_amount') }}" min="0" step="0.01" placeholder="e.g., 500">
                    </div>
                    @error('discount_amount')
                    <span style="color: #dc2626; font-size: 0.85rem;">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <small style="color: #6b7280; display: block; margin-top: -0.5rem; margin-bottom: 1rem;">Choose either percentage or fixed amount discount.</small>

            <div class="form-group">
                <label class="form-label">Promo Code</label>
                <input type="text" name="promo_code" class="form-input" value="{{ old('promo_code') }}" placeholder="e.g., SUMMER2024" style="text-transform: uppercase;">
                @error('promo_code')
                <span style="color: #dc2626; font-size: 0.85rem;">{{ $message }}</span>
                @enderror
                <small style="color: #6b7280;">Optional code that customers can use to apply this promotion.</small>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Start Date <span style="color: #dc2626;">*</span></label>
                    <input type="date" name="start_date" class="form-input" value="{{ old('start_date') }}" required>
                    @error('start_date')
                    <span style="color: #dc2626; font-size: 0.85rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">End Date <span style="color: #dc2626;">*</span></label>
                    <input type="date" name="end_date" class="form-input" value="{{ old('end_date') }}" required>
                    @error('end_date')
                    <span style="color: #dc2626; font-size: 0.85rem;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Promotion Image</label>
                <input type="file" name="image" class="form-input" accept="image/*">
                @error('image')
                <span style="color: #dc2626; font-size: 0.85rem;">{{ $message }}</span>
                @enderror
                <small style="color: #6b7280;">Optional banner image for the promotion.</small>
            </div>

            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} style="width: 18px; height: 18px;">
                    <span>Activate promotion immediately</span>
                </label>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Create Promotion
                </button>
                <a href="{{ route('promotions.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

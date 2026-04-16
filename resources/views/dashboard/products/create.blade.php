@extends('layouts.dashboard')

@section('title', 'Add Product')
@section('page-title', 'Add Product')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Vendor *</label>
                    <select name="vendor_id" class="form-input form-select" required>
                        <option value="">Select Vendor</option>
                        @foreach($vendors as $vendor)
                        <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
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
                    <label class="form-label">Price *</label>
                    <input type="number" name="price" class="form-input" value="{{ old('price') }}" step="0.01" min="0" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-input" value="{{ old('category') }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" class="form-input" value="{{ old('stock') }}" min="0">
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
                <button type="submit" class="btn btn-primary">Save Product</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
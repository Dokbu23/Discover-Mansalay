@extends('layouts.dashboard')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-pink-50 to-white">
    <!-- Hero Section -->
    <div class="relative h-96 w-full bg-gray-200 overflow-hidden">
        @php
            $imagePath = $heritage->image 
                ? asset('storage/' . $heritage->image)
                : (isset($galleryImages) && count($galleryImages) > 0 
                    ? asset($galleryImages[($heritage->id - 1) % count($galleryImages)])
                    : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800');
        @endphp
        <img src="{{ $imagePath }}" alt="{{ $heritage->name }}" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
        <div class="absolute bottom-0 left-0 right-0 p-8 text-white">
            <h1 class="text-4xl font-bold mb-2">{{ $heritage->name }}</h1>
            <p class="text-lg opacity-90">{{ $heritage->location ?? 'Location not specified' }}</p>
        </div>
    </div>

    <!-- Content Section -->
    <div class="max-w-6xl mx-auto px-4 py-12">
        <!-- Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-pink-600">
                <h3 class="text-gray-600 text-sm font-semibold mb-2">ENTRANCE FEE</h3>
                <p class="text-3xl font-bold text-pink-600">
                    @if($heritage->entrance_fee)
                        ₱{{ number_format($heritage->entrance_fee, 2) }}
                    @else
                        <span class="text-lg">Free Entry</span>
                    @endif
                </p>
            </div>
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-pink-600">
                <h3 class="text-gray-600 text-sm font-semibold mb-2">STATUS</h3>
                <p class="text-lg font-semibold">
                    <span class="inline-block px-3 py-1 rounded-full text-white {{ $heritage->is_active ? 'bg-green-500' : 'bg-red-500' }}">
                        {{ $heritage->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </p>
            </div>
        </div>

        <!-- Description -->
        @if($heritage->description)
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">About This Heritage Site</h2>
                <p class="text-gray-700 leading-relaxed">{{ $heritage->description }}</p>
            </div>
        @endif

        <!-- Details -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Details</h2>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dt class="text-gray-600 font-semibold text-sm">Location</dt>
                    <dd class="text-gray-900 mt-1">{{ $heritage->location ?? 'Not specified' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-600 font-semibold text-sm">Last Updated</dt>
                    <dd class="text-gray-900 mt-1">{{ $heritage->updated_at->format('M d, Y') }}</dd>
                </div>
            </dl>
        </div>

        <!-- Actions -->
        <div class="flex gap-4 justify-center mt-12">
            <a href="{{ route('heritage.index') }}" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                Back to Heritage Sites
            </a>
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('heritage.edit', $heritage) }}" class="px-6 py-3 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition">
                        Edit Heritage Site
                    </a>
                @endif
            @endauth
        </div>
    </div>
</div>
@endsection

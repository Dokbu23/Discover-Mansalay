@extends('layouts.dashboard')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-pink-50 to-white">
    <!-- Hero Section -->
    <div class="relative h-96 w-full bg-gray-200 overflow-hidden">
        @php
            $imagePath = $resort->cover_image 
                ? asset('storage/' . $resort->cover_image)
                : (isset($galleryImages) && count($galleryImages) > 0 
                    ? asset($galleryImages[($resort->id + 1) % count($galleryImages)])
                    : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800');
        @endphp
        <img src="{{ $imagePath }}" alt="{{ $resort->name }}" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
        <div class="absolute bottom-0 left-0 right-0 p-8 text-white">
            <h1 class="text-4xl font-bold mb-2">{{ $resort->name }}</h1>
            <p class="text-lg opacity-90">{{ $resort->address ?? 'Address not specified' }}</p>
        </div>
    </div>

    <!-- Content Section -->
    <div class="max-w-6xl mx-auto px-4 py-12">
        <!-- Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-pink-600">
                <h3 class="text-gray-600 text-sm font-semibold mb-2">STATUS</h3>
                <p class="text-lg font-semibold">
                    <span class="inline-block px-3 py-1 rounded-full text-white {{ $resort->is_active ? 'bg-green-500' : 'bg-red-500' }}">
                        {{ $resort->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </p>
            </div>
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-pink-600">
                <h3 class="text-gray-600 text-sm font-semibold mb-2">CONTACT</h3>
                <p class="text-gray-900">{{ $resort->contact_number ?? 'N/A' }}</p>
                <p class="text-gray-900 text-sm">{{ $resort->email ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Description -->
        @if($resort->description)
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">About This Resort</h2>
                <p class="text-gray-700 leading-relaxed">{{ $resort->description }}</p>
            </div>
        @endif

        <!-- Amenities -->
        @if($resort->amenities && count($resort->amenities) > 0)
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Amenities</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($resort->amenities as $amenity)
                        <div class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 text-pink-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            {{ $amenity }}
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Details -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Details</h2>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dt class="text-gray-600 font-semibold text-sm">Address</dt>
                    <dd class="text-gray-900 mt-1">{{ $resort->address ?? 'Not specified' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-600 font-semibold text-sm">Last Updated</dt>
                    <dd class="text-gray-900 mt-1">{{ $resort->updated_at->format('M d, Y') }}</dd>
                </div>
            </dl>
        </div>

        <!-- Actions -->
        <div class="flex gap-4 justify-center mt-12">
            <a href="{{ route('resorts.index') }}" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                Back to Resorts
            </a>
            @auth
                @if(auth()->user()->isAdmin() || auth()->user()->id === $resort->owner_id)
                    <a href="{{ route('resorts.edit', $resort) }}" class="px-6 py-3 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition">
                        Edit Resort
                    </a>
                @endif
            @endauth
        </div>
    </div>
</div>
@endsection

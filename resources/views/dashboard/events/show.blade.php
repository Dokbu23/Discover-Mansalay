@extends('layouts.dashboard')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-pink-50 to-white">
    <!-- Hero Section -->
    <div class="relative h-96 w-full bg-gray-200 overflow-hidden">
        @php
            $imagePath = $event->image 
                ? asset('storage/' . $event->image)
                : (isset($galleryImages) && count($galleryImages) > 0 
                    ? asset($galleryImages[($event->id + 3) % count($galleryImages)])
                    : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800');
        @endphp
        <img src="{{ $imagePath }}" alt="{{ $event->name }}" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
        <div class="absolute bottom-0 left-0 right-0 p-8 text-white">
            <h1 class="text-4xl font-bold mb-2">{{ $event->name }}</h1>
            <p class="text-lg opacity-90">{{ $event->location ?? 'Location not specified' }}</p>
        </div>
    </div>

    <!-- Content Section -->
    <div class="max-w-6xl mx-auto px-4 py-12">
        <!-- Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-pink-600">
                <h3 class="text-gray-600 text-sm font-semibold mb-2">START DATE</h3>
                <p class="text-lg font-semibold text-gray-900">{{ $event->start_date->format('M d, Y') }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-pink-600">
                <h3 class="text-gray-600 text-sm font-semibold mb-2">END DATE</h3>
                <p class="text-lg font-semibold text-gray-900">
                    @if($event->end_date)
                        {{ $event->end_date->format('M d, Y') }}
                    @else
                        <span class="text-gray-500">TBD</span>
                    @endif
                </p>
            </div>
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-pink-600">
                <h3 class="text-gray-600 text-sm font-semibold mb-2">STATUS</h3>
                <p class="text-lg font-semibold">
                    <span class="inline-block px-3 py-1 rounded-full text-white {{ $event->is_featured ? 'bg-yellow-500' : 'bg-blue-500' }}">
                        {{ $event->is_featured ? 'Featured' : 'Regular' }}
                    </span>
                </p>
            </div>
        </div>

        <!-- Description -->
        @if($event->description)
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">About This Event</h2>
                <p class="text-gray-700 leading-relaxed">{{ $event->description }}</p>
            </div>
        @endif

        <!-- Timeline -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Event Timeline</h2>
            <div class="space-y-4">
                <div class="flex">
                    <div class="flex flex-col items-center mr-4">
                        <div class="flex items-center justify-center h-10 w-10 rounded-full bg-pink-600 text-white font-bold">
                            1
                        </div>
                        <div class="w-1 h-12 bg-pink-200"></div>
                    </div>
                    <div class="pt-2">
                        <p class="font-semibold text-gray-800">Event Starts</p>
                        <p class="text-gray-600 text-sm">{{ $event->start_date->format('l, F d, Y') }}</p>
                    </div>
                </div>
                <div class="flex">
                    <div class="flex flex-col items-center mr-4">
                        <div class="flex items-center justify-center h-10 w-10 rounded-full bg-pink-600 text-white font-bold">
                            2
                        </div>
                        @if($event->end_date)
                            <div class="w-1 h-12 bg-pink-200"></div>
                        @endif
                    </div>
                    <div class="pt-2">
                        <p class="font-semibold text-gray-800">Event Ends</p>
                        <p class="text-gray-600 text-sm">
                            @if($event->end_date)
                                {{ $event->end_date->format('l, F d, Y') }}
                            @else
                                <span class="text-gray-500">To be announced</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Details</h2>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dt class="text-gray-600 font-semibold text-sm">Location</dt>
                    <dd class="text-gray-900 mt-1">{{ $event->location ?? 'Not specified' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-600 font-semibold text-sm">Last Updated</dt>
                    <dd class="text-gray-900 mt-1">{{ $event->updated_at->format('M d, Y') }}</dd>
                </div>
            </dl>
        </div>

        <!-- Actions -->
        <div class="flex gap-4 justify-center mt-12">
            <a href="{{ route('events.index') }}" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                Back to Events
            </a>
            @auth
                <a href="{{ route('inquiries.create') }}" class="px-6 py-3 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition">
                    Send Inquiry
                </a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('events.edit', $event) }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Edit Event
                    </a>
                @endif
            @endauth
        </div>
    </div>
</div>
@endsection

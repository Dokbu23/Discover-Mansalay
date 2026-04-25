<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    private function getGalleryImages()
    {
        $galleryImages = [];
        $imageDirectory = public_path('images');

        if (File::exists($imageDirectory)) {
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'];

            foreach (File::allFiles($imageDirectory) as $imageFile) {
                if (stripos($imageFile->getFilename(), 'logo') !== false) {
                    continue;
                }

                if (in_array(strtolower($imageFile->getExtension()), $allowedExtensions, true)) {
                    $galleryImages[] = 'images/' . str_replace('\\', '/', $imageFile->getRelativePathname());
                }
            }

            sort($galleryImages);
        }

        return $galleryImages;
    }

    public function index()
    {
        $events = Event::latest()->paginate(10);
        return view('dashboard.events.index', compact('events'));
    }

    public function show(Event $event)
    {
        $galleryImages = $this->getGalleryImages();
        return view('dashboard.events.show', compact('event', 'galleryImages'));
    }

    public function create()
    {
        return view('dashboard.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'image' => 'nullable|image|max:2048',
            'is_featured' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $validated['is_featured'] = $request->has('is_featured');

        Event::create($validated);

        return redirect()->route('events.index')->with('success', 'Event created successfully!');
    }

    public function edit(Event $event)
    {
        return view('dashboard.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'image' => 'nullable|image|max:2048',
            'is_featured' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $validated['is_featured'] = $request->has('is_featured');

        $event->update($validated);

        return redirect()->route('events.index')->with('success', 'Event updated successfully!');
    }

    public function destroy(Event $event)
    {
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event deleted successfully!');
    }
}

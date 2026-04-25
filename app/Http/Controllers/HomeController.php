<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\HeritageSite;
use App\Models\Product;
use App\Models\Resort;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class HomeController extends Controller
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
        $galleryImages = $this->getGalleryImages();

        // Get active heritage sites
        $heritageSites = HeritageSite::where('is_active', true)
            ->latest()
            ->take(6)
            ->get();

        // Get active resorts with their rooms
        $resorts = Resort::where('is_active', true)
            ->with('rooms')
            ->latest()
            ->take(6)
            ->get();

        // Get upcoming events
        $events = Event::upcoming()
            ->take(4)
            ->get();

        // Get featured products with their vendors
        $products = Product::where('is_available', true)
            ->with('vendor')
            ->latest()
            ->take(8)
            ->get();

        return view('welcome', compact('heritageSites', 'resorts', 'events', 'products', 'galleryImages'));
    }

    public function attractions()
    {
        $galleryImages = $this->getGalleryImages();

        // Get all active heritage sites
        $heritageSites = HeritageSite::where('is_active', true)
            ->latest()
            ->paginate(12, ['*'], 'heritage_page');

        // Get all active resorts with their rooms
        $resorts = Resort::where('is_active', true)
            ->with('rooms')
            ->latest()
            ->paginate(12, ['*'], 'resorts_page');

        // Get all events
        $events = Event::latest()
            ->paginate(12, ['*'], 'events_page');

        return view('attractions', compact('heritageSites', 'resorts', 'events', 'galleryImages'));
    }

    public function heritage()
    {
        $galleryImages = $this->getGalleryImages();

        // Get all active heritage sites with pagination
        $heritageSites = HeritageSite::where('is_active', true)
            ->latest()
            ->paginate(12, ['*'], 'page');

        return view('heritage', compact('heritageSites', 'galleryImages'));
    }

    public function resorts()
    {
        $galleryImages = $this->getGalleryImages();

        // Get all active resorts with their rooms with pagination
        $resorts = Resort::where('is_active', true)
            ->with('rooms')
            ->latest()
            ->paginate(12, ['*'], 'page');

        return view('resorts', compact('resorts', 'galleryImages'));
    }

    public function events()
    {
        $galleryImages = $this->getGalleryImages();

        // Get all events with pagination
        $events = Event::latest()
            ->paginate(12, ['*'], 'page');

        return view('events', compact('events', 'galleryImages'));
    }

    public function products()
    {
        $galleryImages = $this->getGalleryImages();

        // Get all available products with their vendors with pagination
        $products = Product::where('is_available', true)
            ->with('vendor')
            ->latest()
            ->paginate(12, ['*'], 'page');

        return view('products', compact('products', 'galleryImages'));
    }

    public function showPlace($slug)
    {
        $galleryImages = $this->getGalleryImages();

        // Try to find in heritage sites
        if ($heritage = HeritageSite::where('slug', $slug)->first()) {
            return view('places.heritage-show', compact('heritage', 'galleryImages'));
        }

        // Try to find in resorts
        if ($resort = Resort::where('slug', $slug)->first()) {
            return view('places.resort-show', compact('resort', 'galleryImages'));
        }

        // Try to find in events
        if ($event = Event::where('slug', $slug)->first()) {
            return view('places.event-show', compact('event', 'galleryImages'));
        }

        // Not found
        abort(404, 'Place not found.');
    }
}

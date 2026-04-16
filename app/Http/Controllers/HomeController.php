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
    public function index()
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
}

<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Event;
use App\Models\HeritageSite;
use App\Models\Product;
use App\Models\Resort;
use App\Models\Room;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class DashboardController extends Controller
{
    private function getGalleryImages(): array
    {
        $images = [];
        $imageDirectory = public_path('images');

        if (!File::exists($imageDirectory)) {
            return $images;
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'];

        foreach (File::allFiles($imageDirectory) as $imageFile) {
            if (stripos($imageFile->getFilename(), 'logo') !== false) {
                continue;
            }

            if (in_array(strtolower($imageFile->getExtension()), $allowedExtensions, true)) {
                $images[] = 'images/' . str_replace('\\', '/', $imageFile->getRelativePathname());
            }
        }

        sort($images);

        return $images;
    }

    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Route to appropriate dashboard based on role
        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isResortOwner()) {
            return $this->resortOwnerDashboard();
        } elseif ($user->isEnterpriseOwner()) {
            return $this->enterpriseOwnerDashboard();
        } else {
            return $this->touristDashboard();
        }
    }

    /**
     * Admin Dashboard - Full system overview
     */
    private function adminDashboard()
    {
        $galleryImages = $this->getGalleryImages();

        $pendingVendorPayments = User::whereIn('role', ['resort_owner', 'enterprise_owner'])
            ->whereNotNull('vendor_payment_receipt_path')
            ->whereNull('vendor_payment_verified_at')
            ->count();

        $stats = [
            'heritage_sites' => HeritageSite::where('is_active', true)->count(),
            'resorts' => Resort::where('is_active', true)->count(),
            'events' => Event::upcoming()->count(),
            'users' => User::count(),
            'vendors' => Vendor::count(),
            'products' => Product::where('is_available', true)->count(),
            'bookings_pending' => Booking::pending()->count(),
            'bookings_total' => Booking::count(),
        ];

        $recentBookings = Booking::with(['user', 'room.resort'])
            ->latest()
            ->take(5)
            ->get();

        $upcomingEvents = Event::upcoming()->take(5)->get();

        return view('dashboard.admin', compact('stats', 'recentBookings', 'upcomingEvents', 'galleryImages', 'pendingVendorPayments'));
    }

    /**
     * Resort Owner Dashboard - Own resorts and bookings
     */
    private function resortOwnerDashboard()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $galleryImages = $this->getGalleryImages();
        
        $resorts = $user->resorts()->with('rooms')->get();
        $resortIds = $resorts->pluck('id');
        
        $roomIds = Room::whereIn('resort_id', $resortIds)->pluck('id');
        
        $stats = [
            'resorts' => $resorts->count(),
            'rooms' => Room::whereIn('resort_id', $resortIds)->count(),
            'bookings_pending' => Booking::whereIn('room_id', $roomIds)->where('status', 'pending')->count(),
            'bookings_confirmed' => Booking::whereIn('room_id', $roomIds)->where('status', 'confirmed')->count(),
            'bookings_total' => Booking::whereIn('room_id', $roomIds)->count(),
            'total_revenue' => Booking::whereIn('room_id', $roomIds)->where('status', 'confirmed')->sum('total_price'),
        ];

        $recentBookings = Booking::with(['user', 'room.resort'])
            ->whereIn('room_id', $roomIds)
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.resort-owner', compact('stats', 'resorts', 'recentBookings', 'galleryImages'));
    }

    /**
     * Enterprise Owner Dashboard - Vendor products and sales
     */
    private function enterpriseOwnerDashboard()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $galleryImages = $this->getGalleryImages();
        
        $vendor = $user->vendor;
        
        if (!$vendor) {
            $stats = [
                'products' => 0,
                'total_stock' => 0,
                'categories' => 0,
            ];
            $products = collect();
            
            return view('dashboard.enterprise-owner', compact('stats', 'vendor', 'products', 'galleryImages'));
        }

        $products = $vendor->products()->latest()->get();
        
        $stats = [
            'products' => $products->count(),
            'total_stock' => $products->sum('stock'),
            'categories' => $products->pluck('category')->unique()->count(),
            'available_products' => $products->where('is_available', true)->count(),
        ];

        return view('dashboard.enterprise-owner', compact('stats', 'vendor', 'products', 'galleryImages'));
    }

    /**
     * Tourist Dashboard - Bookings and saved items
     */
    private function touristDashboard()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $galleryImages = $this->getGalleryImages();
        
        $bookings = $user->bookings()->with('room.resort')->latest()->get();
        
        $stats = [
            'total_bookings' => $bookings->count(),
            'pending_bookings' => $bookings->where('status', 'pending')->count(),
            'confirmed_bookings' => $bookings->where('status', 'confirmed')->count(),
            'completed_trips' => $bookings->where('status', 'completed')->count(),
        ];

        $upcomingBookings = $user->bookings()
            ->with('room.resort')
            ->where('check_in', '>=', now())
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('check_in')
            ->take(5)
            ->get();

        $upcomingEvents = Event::upcoming()->take(4)->get();
        $featuredResorts = Resort::where('is_active', true)->orderBy('rating', 'desc')->take(4)->get();

        return view('dashboard.tourist', compact('stats', 'bookings', 'upcomingBookings', 'upcomingEvents', 'featuredResorts', 'galleryImages'));
    }
}

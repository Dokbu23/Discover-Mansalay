<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AwatiBuyerDashboardController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HeritageSiteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\PublicChatbotController;
use App\Http\Controllers\ResortController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SupportRequestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/attractions', [HomeController::class, 'attractions'])->name('attractions');
Route::get('/heritage-sites', [HomeController::class, 'heritage'])->name('heritage.public');
Route::get('/resorts', [HomeController::class, 'resorts'])->name('resorts.public');
Route::get('/events', [HomeController::class, 'events'])->name('events.public');
Route::get('/products', [HomeController::class, 'products'])->name('products.public');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Register
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Public chatbot for login/help queries
Route::post('/chatbot/ask', [PublicChatbotController::class, 'ask'])->name('chatbot.ask');

/*
|--------------------------------------------------------------------------
| Dashboard Routes (Protected)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Main Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Vendor Payment Submission (Vendor Roles)
    Route::post('/vendor-payment/submit', [UserController::class, 'submitVendorPayment'])->name('vendor.payment.submit');


    Route::middleware('approved')->group(function () {

        // Awati Buyer Dashboard
        Route::get('/awati/buyer-dashboard', [AwatiBuyerDashboardController::class, 'index'])->name('awati.buyer.dashboard');

    // Heritage Sites
    Route::get('/heritage', [HeritageSiteController::class, 'index'])->name('heritage.index');
    Route::get('/heritage/create', [HeritageSiteController::class, 'create'])->name('heritage.create');
    Route::post('/heritage', [HeritageSiteController::class, 'store'])->name('heritage.store');
    Route::get('/heritage/{heritage}', [HeritageSiteController::class, 'show'])->name('heritage.show');
    Route::get('/heritage/{heritage}/edit', [HeritageSiteController::class, 'edit'])->name('heritage.edit');
    Route::put('/heritage/{heritage}', [HeritageSiteController::class, 'update'])->name('heritage.update');
    Route::delete('/heritage/{heritage}', [HeritageSiteController::class, 'destroy'])->name('heritage.destroy');

    // Resorts
    Route::get('/dashboard/resorts', [ResortController::class, 'index'])->name('resorts.index');
    Route::get('/resorts/{resort}', [ResortController::class, 'show'])->name('resorts.show');
    Route::resource('resorts', ResortController::class)->except(['show', 'index']);

    // Rooms
    Route::resource('rooms', RoomController::class)->except(['show']);

    // Events
    Route::get('/dashboard/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::resource('events', EventController::class)->except(['show', 'index']);

    // Vendors
    Route::get('/vendor/settings', [VendorController::class, 'settings'])->name('vendors.settings');
    Route::post('/vendor/settings', [VendorController::class, 'saveSettings'])->name('vendors.settings.save');
    Route::resource('vendors', VendorController::class)->except(['show']);

    // Products
    Route::get('/dashboard/products', [ProductController::class, 'index'])->name('products.index');
    Route::resource('products', ProductController::class)->except(['show', 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/shop/cart', [ProductController::class, 'cart'])->name('products.cart');
    Route::post('/products/{product}/cart', [ProductController::class, 'addToCart'])->name('products.cart.add');
    Route::patch('/shop/cart/{product}', [ProductController::class, 'updateCart'])->name('products.cart.update');
    Route::delete('/shop/cart/{product}', [ProductController::class, 'removeFromCart'])->name('products.cart.remove');
    Route::patch('/products/{product}/approve', [ProductController::class, 'approve'])->name('products.approve');
    Route::patch('/products/{product}/reject', [ProductController::class, 'reject'])->name('products.reject');

    // Bookings
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.updateStatus');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');

    // Users (Admin Only)
    Route::resource('users', UserController::class);
    Route::patch('/users/{user}/approve', [UserController::class, 'approve'])->name('users.approve');
    Route::delete('/users/{user}/reject', [UserController::class, 'reject'])->name('users.reject');
    Route::patch('/users/{user}/payment-verify', [UserController::class, 'verifyVendorPayment'])->name('users.payment.verify');
    Route::get('/users/{user}/payment-receipt', [UserController::class, 'downloadVendorPaymentReceipt'])->name('users.payment.receipt');
    Route::get('/users/{user}/payment-receipt/view', [UserController::class, 'viewVendorPaymentReceipt'])->name('users.payment.receipt.view');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

    // Inquiries
    Route::get('/inquiries', [InquiryController::class, 'index'])->name('inquiries.index');
    Route::get('/inquiries/create', [InquiryController::class, 'create'])->name('inquiries.create');
    Route::post('/inquiries', [InquiryController::class, 'store'])->name('inquiries.store');
    Route::get('/inquiries/{inquiry}', [InquiryController::class, 'show'])->name('inquiries.show');
    Route::patch('/inquiries/{inquiry}/reply', [InquiryController::class, 'reply'])->name('inquiries.reply');
    Route::patch('/inquiries/{inquiry}/close', [InquiryController::class, 'close'])->name('inquiries.close');
    Route::delete('/inquiries/{inquiry}', [InquiryController::class, 'destroy'])->name('inquiries.destroy');

    // Promotions
    Route::resource('promotions', PromotionController::class)->except(['show']);

    // Support Requests (Contact Admin Bot)
    Route::get('/support', [SupportRequestController::class, 'index'])->name('support.index');
    Route::post('/support', [SupportRequestController::class, 'store'])->name('support.store');
    Route::get('/support/{supportRequest}', [SupportRequestController::class, 'show'])->name('support.show');
    Route::put('/support/{supportRequest}', [SupportRequestController::class, 'update'])->name('support.update');
        Route::get('/support/open-count', [SupportRequestController::class, 'getOpenCount'])->name('support.open-count');
    });
});

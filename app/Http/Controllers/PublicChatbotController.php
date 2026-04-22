<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\HeritageSite;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\Resort;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicChatbotController extends Controller
{
    public function ask(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|min:2|max:500',
        ]);

        $message = trim($validated['message']);
        $normalized = strtolower($message);

        if ($this->containsAny($normalized, ['hello', 'hi', 'hey', 'good morning', 'good afternoon', 'good evening'])) {
            return $this->response(
                "Hi! I can help with login, registration, resorts, rooms, products, events, and promotions in Discover Mansalay.",
                ['How many resorts are available?', 'Show active promotions', 'I cannot login', 'Upcoming events']
            );
        }

        if ($this->containsAny($normalized, ['cannot login', 'cant login', 'can\'t login', 'login problem', 'wrong password', 'password'])) {
            return $this->response(
                "For login issues: confirm your email/password, then try again. If your account is newly registered, it may still be waiting for admin approval. You can also contact admin support after login.",
                ['How to create an account?', 'Show support options']
            );
        }

        if ($this->containsAny($normalized, ['register', 'sign up', 'create account'])) {
            return $this->response(
                "To register, click Create one on this page and submit your details. Your account may require admin approval before full access.",
                ['What can I do after login?', 'How many resorts are available?']
            );
        }

        if ($this->containsAny($normalized, ['how many', 'count', 'ilan'])) {
            return $this->response($this->buildCountSummary(), ['Show active promotions', 'Upcoming events', 'Available rooms']);
        }

        if ($this->containsAny($normalized, ['promotion', 'promo', 'discount'])) {
            return $this->response($this->buildPromotionSummary(), ['Promo codes', 'Upcoming events', 'Resorts']);
        }

        if ($this->containsAny($normalized, ['event', 'festival', 'upcoming'])) {
            return $this->response($this->buildEventSummary(), ['How many attractions are active?', 'Show active promotions']);
        }

        if ($this->containsAny($normalized, ['resort', 'room', 'accommodation', 'stay'])) {
            return $this->response($this->buildResortSummary(), ['Available rooms', 'Cheapest room', 'How many resorts are available?']);
        }

        if ($this->containsAny($normalized, ['product', 'souvenir', 'shop', 'pasalubong'])) {
            return $this->response($this->buildProductSummary(), ['How many products are available?', 'Show active promotions']);
        }

        $searchReply = $this->buildEntitySearchSummary($message);
        if ($searchReply !== null) {
            return $this->response($searchReply, ['Upcoming events', 'Active promotions', 'Available resorts']);
        }

        return $this->response(
            "I can help with website information like resorts, rooms, products, events, promotions, and account steps. Try asking: 'How many resorts are available?' or 'Show active promotions'.",
            ['How many resorts are available?', 'Show active promotions', 'Upcoming events', 'I cannot login']
        );
    }

    private function response(string $reply, array $suggestions = []): JsonResponse
    {
        return response()->json([
            'success' => true,
            'reply' => $reply,
            'suggestions' => array_slice($suggestions, 0, 4),
        ]);
    }

    private function containsAny(string $text, array $keywords): bool
    {
        foreach ($keywords as $keyword) {
            if (str_contains($text, $keyword)) {
                return true;
            }
        }

        return false;
    }

    private function buildCountSummary(): string
    {
        $resortCount = Resort::where('is_active', true)->count();
        $roomCount = Room::where('is_available', true)->count();
        $productCount = Product::where('is_available', true)->where('is_approved', true)->count();
        $heritageCount = HeritageSite::where('is_active', true)->count();
        $eventCount = Event::whereDate('start_date', '>=', now()->toDateString())->count();
        $promoCount = Promotion::where('is_active', true)
            ->whereDate('start_date', '<=', now()->toDateString())
            ->whereDate('end_date', '>=', now()->toDateString())
            ->count();

        return "Current website data: {$resortCount} active resorts, {$roomCount} available rooms, {$productCount} available products, {$heritageCount} active heritage sites, {$eventCount} upcoming events, and {$promoCount} active promotions.";
    }

    private function buildPromotionSummary(): string
    {
        $promotions = Promotion::where('is_active', true)
            ->whereDate('start_date', '<=', now()->toDateString())
            ->whereDate('end_date', '>=', now()->toDateString())
            ->orderBy('end_date')
            ->take(3)
            ->get();

        if ($promotions->isEmpty()) {
            return 'There are no active promotions right now. Please check back soon for new offers.';
        }

        $lines = $promotions->map(function ($promo) {
            $discount = $promo->discount_percentage
                ? rtrim(rtrim(number_format((float) $promo->discount_percentage, 2, '.', ''), '0'), '.') . '% off'
                : 'PHP ' . number_format((float) $promo->discount_amount, 2) . ' off';

            $code = $promo->promo_code ? " (Code: {$promo->promo_code})" : '';

            return "- {$promo->title}: {$discount}{$code}, valid until {$promo->end_date->format('M d, Y')}";
        })->implode("\n");

        return "Here are the current active promotions:\n{$lines}";
    }

    private function buildEventSummary(): string
    {
        $events = Event::whereDate('start_date', '>=', now()->toDateString())
            ->orderBy('start_date')
            ->take(3)
            ->get();

        if ($events->isEmpty()) {
            return 'No upcoming events are posted yet.';
        }

        $lines = $events->map(function ($event) {
            return "- {$event->name} at {$event->location} ({$event->start_date->format('M d, Y')} to {$event->end_date->format('M d, Y')})";
        })->implode("\n");

        return "Upcoming events:\n{$lines}";
    }

    private function buildResortSummary(): string
    {
        $resortCount = Resort::where('is_active', true)->count();
        $roomCount = Room::where('is_available', true)->count();

        $cheapestRoom = Room::where('is_available', true)
            ->with('resort:id,name')
            ->orderBy('price_per_night')
            ->first();

        $topResorts = Resort::where('is_active', true)
            ->latest()
            ->take(3)
            ->pluck('name')
            ->implode(', ');

        if (!$cheapestRoom) {
            return "There are {$resortCount} active resorts right now. Room availability is currently being updated.";
        }

        $resortName = optional($cheapestRoom->resort)->name ?: 'Unknown resort';
        $price = number_format((float) $cheapestRoom->price_per_night, 2);
        $topResortText = $topResorts ?: 'No resort list available yet';

        return "There are {$resortCount} active resorts and {$roomCount} available rooms. Latest featured resorts: {$topResortText}. Cheapest available room right now is {$cheapestRoom->name} at {$resortName} for PHP {$price}/night.";
    }

    private function buildProductSummary(): string
    {
        $productCount = Product::where('is_available', true)->where('is_approved', true)->count();

        $sampleProducts = Product::where('is_available', true)
            ->where('is_approved', true)
            ->latest()
            ->take(3)
            ->get(['name', 'price', 'category']);

        if ($sampleProducts->isEmpty()) {
            return 'No available products are listed right now.';
        }

        $lines = $sampleProducts->map(function ($product) {
            $category = $product->category ?: 'General';
            return "- {$product->name} ({$category}) - PHP " . number_format((float) $product->price, 2);
        })->implode("\n");

        return "There are {$productCount} available products. Here are sample items:\n{$lines}";
    }

    private function buildEntitySearchSummary(string $rawMessage): ?string
    {
        $keywords = collect(preg_split('/\s+/', strtolower($rawMessage)))
            ->filter(function ($word) {
                return strlen($word) >= 3;
            })
            ->take(5)
            ->values();

        if ($keywords->isEmpty()) {
            return null;
        }

        $resort = Resort::where('is_active', true)
            ->where(function ($query) use ($keywords) {
                foreach ($keywords as $word) {
                    $query->orWhere('name', 'like', "%{$word}%");
                }
            })
            ->first();

        if ($resort) {
            return "I found a matching resort: {$resort->name} in {$resort->address}. You can explore it on the website listings.";
        }

        $heritageSite = HeritageSite::where('is_active', true)
            ->where(function ($query) use ($keywords) {
                foreach ($keywords as $word) {
                    $query->orWhere('name', 'like', "%{$word}%");
                }
            })
            ->first();

        if ($heritageSite) {
            $fee = number_format((float) $heritageSite->entrance_fee, 2);
            return "I found a matching heritage site: {$heritageSite->name} at {$heritageSite->location}, entrance fee PHP {$fee}.";
        }

        $event = Event::where(function ($query) use ($keywords) {
            foreach ($keywords as $word) {
                $query->orWhere('name', 'like', "%{$word}%");
            }
        })->orderBy('start_date')->first();

        if ($event) {
            return "I found an event: {$event->name} at {$event->location} from {$event->start_date->format('M d, Y')} to {$event->end_date->format('M d, Y')}.";
        }

        $product = Product::where('is_available', true)
            ->where('is_approved', true)
            ->where(function ($query) use ($keywords) {
                foreach ($keywords as $word) {
                    $query->orWhere('name', 'like', "%{$word}%");
                }
            })
            ->first();

        if ($product) {
            $price = number_format((float) $product->price, 2);
            return "I found a matching product: {$product->name} for PHP {$price}.";
        }

        return null;
    }
}

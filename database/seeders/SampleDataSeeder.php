<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\HeritageSite;
use App\Models\Product;
use App\Models\Resort;
use App\Models\Room;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SampleDataSeeder extends Seeder
{
    public function run()
    {
        $defaultPassword = Hash::make('password');

        // Create admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@discovermansalay.com'],
            [
                'name' => 'Admin',
                'password' => $defaultPassword,
                'role' => 'admin',
                'is_active' => true,
                'is_approved' => true,
                'approved_at' => now(),
            ]
        );

        // Create resort owner user
        $resortOwner = User::updateOrCreate(
            ['email' => 'resort@discovermansalay.com'],
            [
                'name' => 'Resort Owner',
                'password' => $defaultPassword,
                'role' => 'resort_owner',
                'is_active' => true,
                'is_approved' => true,
                'approved_at' => now(),
            ]
        );

        // Create enterprise owner user (vendor)
        $enterpriseOwner = User::updateOrCreate(
            ['email' => 'vendor@discovermansalay.com'],
            [
                'name' => 'Enterprise Owner',
                'password' => $defaultPassword,
                'role' => 'enterprise_owner',
                'is_active' => true,
                'is_approved' => true,
                'approved_at' => now(),
            ]
        );

        // Create tourist user
        $tourist = User::updateOrCreate(
            ['email' => 'tourist@discovermansalay.com'],
            [
                'name' => 'Juan dela Cruz',
                'password' => $defaultPassword,
                'role' => 'tourist',
                'is_active' => true,
                'is_approved' => true,
                'approved_at' => now(),
            ]
        );

        // Heritage Sites
        $heritageSites = [
            [
                'name' => 'Buyayao Island',
                'description' => 'A pristine beach paradise with white sand and crystal-clear waters. Perfect for swimming, snorkeling, and beach camping. One of the most beautiful islands in Oriental Mindoro.',
                'location' => 'Buyayao, Mansalay',
                'entrance_fee' => 50.00,
                'is_active' => true,
            ],
            [
                'name' => 'Ammonite Fossil Site',
                'description' => 'Explore the fossil-rich areas that make Mansalay the Ammonite Capital of the Philippines. A unique geological wonder showcasing millions of years of history.',
                'location' => 'Barangay Maluanluan, Mansalay',
                'entrance_fee' => 30.00,
                'is_active' => true,
            ],
            [
                'name' => 'Mt. Malasimbo',
                'description' => 'Majestic mountain offering breathtaking panoramic views of Oriental Mindoro. Popular for hiking and trekking adventures with trails suitable for beginners and experienced hikers.',
                'location' => 'Mansalay-Bulalacao Boundary',
                'entrance_fee' => 100.00,
                'is_active' => true,
            ],
            [
                'name' => 'Mangyan Village',
                'description' => 'Experience the rich heritage of indigenous Mangyan people. Learn about their traditional art, Hanunuo script, weaving, and warm hospitality.',
                'location' => 'Sitio Panaytayan, Mansalay',
                'entrance_fee' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Wasig Falls',
                'description' => 'A hidden gem featuring cascading waterfalls surrounded by lush tropical forest. Perfect for nature lovers and adventure seekers.',
                'location' => 'Barangay Wasig, Mansalay',
                'entrance_fee' => 20.00,
                'is_active' => true,
            ],
            [
                'name' => 'Poblacion Heritage Church',
                'description' => 'Historic Spanish-era church showcasing beautiful colonial architecture. A testament to Mansalay\'s rich cultural and religious history.',
                'location' => 'Poblacion, Mansalay',
                'entrance_fee' => 0,
                'is_active' => true,
            ],
        ];

        foreach ($heritageSites as $site) {
            HeritageSite::firstOrCreate(['name' => $site['name']], $site);
        }

        // Resorts
        $resorts = [
            [
                'owner_id' => $resortOwner->id,
                'name' => 'Buyayao Beach Resort',
                'description' => 'Beachfront resort offering stunning views of the ocean. Features native-style cottages, fresh seafood, and island hopping tours.',
                'address' => 'Buyayao Island, Mansalay, Oriental Mindoro',
                'contact_number' => '0917-123-4567',
                'email' => 'buyayaoresort@email.com',
                'rating' => 4.5,
                'is_active' => true,
            ],
            [
                'owner_id' => $resortOwner->id,
                'name' => 'Mansalay Eco Lodge',
                'description' => 'Eco-friendly accommodation surrounded by nature. Perfect for those seeking a peaceful retreat with sustainable practices.',
                'address' => 'Barangay B. del Mundo, Mansalay',
                'contact_number' => '0918-234-5678',
                'email' => 'ecolodge@email.com',
                'rating' => 4.2,
                'is_active' => true,
            ],
            [
                'owner_id' => $resortOwner->id,
                'name' => 'Paradise Inn Mansalay',
                'description' => 'Comfortable hotel in the town center with modern amenities. Ideal for business travelers and tourists exploring Mansalay.',
                'address' => 'Poblacion, Mansalay, Oriental Mindoro',
                'contact_number' => '0919-345-6789',
                'email' => 'paradiseinn@email.com',
                'rating' => 4.0,
                'is_active' => true,
            ],
        ];

        foreach ($resorts as $resortData) {
            $resort = Resort::firstOrCreate(['name' => $resortData['name']], $resortData);
            
            // Add rooms to each resort
            $roomTypes = [
                ['name' => 'Standard Room', 'type' => 'standard', 'capacity' => 2, 'price_per_night' => 1500.00],
                ['name' => 'Deluxe Room', 'type' => 'deluxe', 'capacity' => 3, 'price_per_night' => 2500.00],
                ['name' => 'Family Suite', 'type' => 'suite', 'capacity' => 5, 'price_per_night' => 4000.00],
            ];

            foreach ($roomTypes as $room) {
                Room::firstOrCreate(
                    ['resort_id' => $resort->id, 'name' => $room['name']],
                    array_merge($room, [
                        'resort_id' => $resort->id,
                        'is_available' => true,
                    ])
                );
            }
        }

        // Events
        $events = [
            [
                'name' => 'Mansalay Founding Anniversary',
                'description' => 'Celebrate the founding anniversary of Mansalay with parades, cultural performances, street dancing, and local food festivals.',
                'location' => 'Municipal Grounds, Poblacion',
                'start_date' => now()->addDays(30),
                'end_date' => now()->addDays(32),
                'is_featured' => true,
            ],
            [
                'name' => 'Ammonite Festival',
                'description' => 'Annual celebration honoring Mansalay\'s unique geological heritage. Features fossil exhibits, educational tours, and cultural shows.',
                'location' => 'Barangay Maluanluan',
                'start_date' => now()->addDays(60),
                'end_date' => now()->addDays(61),
                'is_featured' => true,
            ],
            [
                'name' => 'Mangyan Cultural Day',
                'description' => 'A day dedicated to celebrating Mangyan culture featuring traditional music, dance, weaving demonstrations, and indigenous crafts.',
                'location' => 'Mangyan Village, Mansalay',
                'start_date' => now()->addDays(45),
                'end_date' => now()->addDays(45),
                'is_featured' => false,
            ],
            [
                'name' => 'Beach Clean-up Drive',
                'description' => 'Community event to preserve the beauty of Buyayao Island. Volunteers welcome! Free lunch and certificate provided.',
                'location' => 'Buyayao Island',
                'start_date' => now()->addDays(14),
                'end_date' => now()->addDays(14),
                'is_featured' => false,
            ],
        ];

        foreach ($events as $event) {
            Event::firstOrCreate(['name' => $event['name']], $event);
        }

        // Vendors
        $vendors = [
            [
                'user_id' => $enterpriseOwner->id,
                'name' => 'Mansalay Pasalubong Center',
                'description' => 'Official pasalubong center featuring the best local products, delicacies, and handicrafts from Mansalay.',
                'address' => 'Public Market, Poblacion, Mansalay',
                'contact_number' => '0920-456-7890',
                'type' => 'pasalubong_center',
            ],
            [
                'user_id' => $enterpriseOwner->id,
                'name' => 'Awati Handicrafts',
                'description' => 'Authentic Mangyan-made handicrafts including woven bags, baskets, and traditional accessories.',
                'address' => 'Mangyan Village, Mansalay',
                'contact_number' => '0921-567-8901',
                'type' => 'awati',
            ],
            [
                'user_id' => $enterpriseOwner->id,
                'name' => 'Lola Nena\'s Delicacies',
                'description' => 'Home of the famous Mansalay kakanin and native delicacies. Made with love using traditional recipes.',
                'address' => 'Barangay Rosacara, Mansalay',
                'contact_number' => '0922-678-9012',
                'type' => 'other',
            ],
        ];

        foreach ($vendors as $vendorData) {
            $vendor = Vendor::firstOrCreate(['name' => $vendorData['name']], $vendorData);
        }

        // Products
        $products = [
            // Pasalubong Center products
            [
                'vendor_name' => 'Mansalay Pasalubong Center',
                'name' => 'Dried Pusit',
                'description' => 'Premium dried squid from the waters of Mansalay. Perfect for pulutan or cooking.',
                'price' => 250.00,
                'category' => 'Seafood',
                'stock' => 50,
                'image' => 'products/dried-pusit.jpg',
                'is_available' => true,
            ],
            [
                'vendor_name' => 'Mansalay Pasalubong Center',
                'name' => 'Banana Chips',
                'description' => 'Crispy banana chips made from locally grown saba bananas.',
                'price' => 80.00,
                'category' => 'Snacks',
                'stock' => 100,
                'image' => 'products/banana-chips.jpg',
                'is_available' => true,
            ],
            [
                'vendor_name' => 'Mansalay Pasalubong Center',
                'name' => 'Ammonite Souvenir Keychain',
                'description' => 'Handcrafted keychain featuring the iconic ammonite fossil design.',
                'price' => 75.00,
                'category' => 'Souvenirs',
                'stock' => 200,
                'image' => 'products/ammonite-keychain.jpg',
                'is_available' => true,
            ],
            // Awati products
            [
                'vendor_name' => 'Awati Handicrafts',
                'name' => 'Mangyan Woven Bag',
                'description' => 'Traditional hand-woven bag made by Mangyan artisans using native materials.',
                'price' => 450.00,
                'category' => 'Handicrafts',
                'stock' => 30,
                'image' => 'products/mangyan-bag.jpg',
                'is_available' => true,
            ],
            [
                'vendor_name' => 'Awati Handicrafts',
                'name' => 'Nito Basket',
                'description' => 'Intricately woven basket made from nito vine. Perfect for home decoration.',
                'price' => 350.00,
                'category' => 'Handicrafts',
                'stock' => 25,
                'image' => 'products/nito-basket.jpg',
                'is_available' => true,
            ],
            [
                'vendor_name' => 'Awati Handicrafts',
                'name' => 'Beaded Bracelet',
                'description' => 'Colorful beaded bracelet with traditional Mangyan patterns.',
                'price' => 120.00,
                'category' => 'Accessories',
                'stock' => 75,
                'image' => 'products/beaded-bracelet.jpg',
                'is_available' => true,
            ],
            [
                'vendor_name' => 'Awati Handicrafts',
                'name' => 'Handwoven Coin Purse',
                'description' => 'Compact handwoven coin purse made by Mangyan artisans using native fibers.',
                'price' => 180.00,
                'category' => 'Accessories',
                'stock' => 45,
                'image' => 'products/handwoven-coin-purse.jpg',
                'is_available' => true,
            ],
            [
                'vendor_name' => 'Awati Handicrafts',
                'name' => 'Mangyan Table Runner',
                'description' => 'Traditional woven table runner featuring indigenous Mangyan patterns.',
                'price' => 520.00,
                'category' => 'Home Decor',
                'stock' => 20,
                'image' => 'products/mangyan-table-runner.jpg',
                'is_available' => true,
            ],
            // Lola Nena's products
            [
                'vendor_name' => 'Lola Nena\'s Delicacies',
                'name' => 'Suman sa Lihiya',
                'description' => 'Traditional Filipino rice cake wrapped in banana leaves. Best paired with ripe mango.',
                'price' => 60.00,
                'category' => 'Kakanin',
                'stock' => 40,
                'image' => 'products/suman.jpg',
                'is_available' => true,
            ],
            [
                'vendor_name' => 'Lola Nena\'s Delicacies',
                'name' => 'Bibingka',
                'description' => 'Classic rice cake cooked in clay pots, topped with salted egg and cheese.',
                'price' => 45.00,
                'category' => 'Kakanin',
                'stock' => 60,
                'image' => 'products/bibingka.jpg',
                'is_available' => true,
            ],
        ];

        foreach ($products as $productData) {
            $vendor = Vendor::where('name', $productData['vendor_name'])->first();
            if ($vendor) {
                unset($productData['vendor_name']);
                Product::firstOrCreate(
                    ['name' => $productData['name'], 'vendor_id' => $vendor->id],
                    array_merge($productData, ['vendor_id' => $vendor->id])
                );
            }
        }

        $this->command->info('Sample data seeded successfully!');
        $this->command->info('');
        $this->command->info('Test Accounts (password: password)');
        $this->command->info('- Admin: admin@discovermansalay.com');
        $this->command->info('- Resort Owner: resort@discovermansalay.com');
        $this->command->info('- Vendor: vendor@discovermansalay.com');
        $this->command->info('- Tourist: tourist@discovermansalay.com');
    }
}

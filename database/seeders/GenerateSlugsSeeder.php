<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\HeritageSite;
use App\Models\Resort;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GenerateSlugsSeeder extends Seeder
{
    public function run()
    {
        // Generate slugs for heritage sites
        HeritageSite::whereNull('slug')->each(function ($site) {
            $site->update([
                'slug' => Str::slug($site->name . '-' . uniqid()),
            ]);
        });

        // Generate slugs for resorts
        Resort::whereNull('slug')->each(function ($resort) {
            $resort->update([
                'slug' => Str::slug($resort->name . '-' . uniqid()),
            ]);
        });

        // Generate slugs for events
        Event::whereNull('slug')->each(function ($event) {
            $event->update([
                'slug' => Str::slug($event->name . '-' . uniqid()),
            ]);
        });

        $this->command->info('Slugs generated successfully!');
    }
}

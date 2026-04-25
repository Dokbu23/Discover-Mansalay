<?php

namespace App\Providers;

use App\Models\Resort;
use App\Models\Product;
use App\Models\Vendor;
use App\Policies\ProductPolicy;
use App\Policies\ResortPolicy;
use App\Policies\VendorPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Resort::class => ResortPolicy::class,
        Product::class => ProductPolicy::class,
        Vendor::class => VendorPolicy::class,
    ];
    /**
     * Register any authentication / authorization services.
     *
     * @return void
     * 
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}

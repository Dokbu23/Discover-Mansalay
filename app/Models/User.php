<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'is_approved',
        'approved_at',
        'vendor_payment_receipt_path',
        'vendor_payment_submitted_at',
        'vendor_payment_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
        'vendor_payment_submitted_at' => 'datetime',
        'vendor_payment_verified_at' => 'datetime',
    ];

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is resort owner
     */
    public function isResortOwner()
    {
        return $this->role === 'resort_owner';
    }

    /**
     * Check if user is enterprise owner (vendor)
     */
    public function isEnterpriseOwner()
    {
        return $this->role === 'enterprise_owner';
    }

    /**
     * Check if user is a vendor role
     */
    public function isVendorRole()
    {
        return in_array($this->role, ['resort_owner', 'enterprise_owner'], true);
    }

    /**
     * Check if vendor payment was submitted
     */
    public function hasSubmittedVendorPayment()
    {
        return $this->vendor_payment_receipt_path !== null;
    }

    /**
     * Check if vendor payment was verified
     */
    public function hasVerifiedVendorPayment()
    {
        return $this->vendor_payment_verified_at !== null;
    }

    /**
     * Check if user is tourist
     */
    public function isTourist()
    {
        return $this->role === 'tourist' || $this->role === null;
    }

    /**
     * Get the vendor profile
     */
    public function vendor()
    {
        return $this->hasOne(Vendor::class);
    }

    /**
     * Get the resorts owned by this user
     */
    public function resorts()
    {
        return $this->hasMany(Resort::class, 'owner_id');
    }

    /**
     * Get the user's bookings
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}

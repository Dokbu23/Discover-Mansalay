<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'resort_id',
        'name',
        'type',
        'capacity',
        'price_per_night',
        'amenities',
        'images',
        'is_available',
    ];

    protected $casts = [
        'amenities' => 'array',
        'images' => 'array',
        'price_per_night' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    public function resort()
    {
        return $this->belongsTo(Resort::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}

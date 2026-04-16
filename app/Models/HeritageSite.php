<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeritageSite extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'location',
        'entrance_fee',
        'image',
        'is_active',
    ];

    protected $casts = [
        'entrance_fee' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}

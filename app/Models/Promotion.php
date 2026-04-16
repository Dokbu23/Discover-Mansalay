<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'resort_id',
        'title',
        'description',
        'discount_percentage',
        'discount_amount',
        'promo_code',
        'start_date',
        'end_date',
        'is_active',
        'image',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'discount_percentage' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function resort()
    {
        return $this->belongsTo(Resort::class);
    }

    public function isValid()
    {
        return $this->is_active && 
               $this->start_date <= now() && 
               $this->end_date >= now();
    }
}

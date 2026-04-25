<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class HeritageSite extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
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

    // TODO: Uncomment after running migrations
    // public function getRouteKeyName()
    // {
    //     return 'slug';
    // }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name . '-' . uniqid());
            }
        });
    }
}

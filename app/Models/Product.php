<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'title',
        'description',
        'hours',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'hours' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    // Relationships

    public function productPrices(): HasMany
    {
        return $this->hasMany(ProductPrice::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}

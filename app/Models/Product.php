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
        'max_persons',
        'min_age',
        'max_age',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'hours' => 'integer',
            'max_persons' => 'integer',
            'min_age' => 'integer',
            'max_age' => 'integer',
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

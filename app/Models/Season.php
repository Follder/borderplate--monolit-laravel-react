<?php

namespace App\Models;

use App\Enums\SeasonType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Season extends Model
{
    protected $fillable = [
        'name',
        'type',
        'date_from',
        'date_to',
    ];

    protected function casts(): array
    {
        return [
            'type' => SeasonType::class,
            'date_from' => 'date',
            'date_to' => 'date',
        ];
    }

    // Relationships

    public function productPrices(): HasMany
    {
        return $this->hasMany(ProductPrice::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_prices')
            ->withPivot(['price', 'additional_price']);
    }
}

<?php

namespace App\Models;

use App\Enums\SeasonType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPrice extends Model
{
    protected $fillable = [
        'product_id',
        'season_type',
        'price',
        'additional_price',
    ];

    protected function casts(): array
    {
        return [
            'season_type' => SeasonType::class,
            'price' => 'integer',
            'additional_price' => 'integer',
        ];
    }

    // Relationships

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

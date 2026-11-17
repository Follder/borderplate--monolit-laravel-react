<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderPerson extends Model
{
    protected $fillable = [
        'order_id',
        'person_id',
        'price',
        'is_primary',
        'receipt_url',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'is_primary' => 'boolean',
        ];
    }

    // Relationships

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}

<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'lesson_date',
        'hours',
        'base_price',
        'extra_person_price',
        'total_price',
        'status',
        'payment_id',
        'payment_url',
        'ticket_url',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'lesson_date' => 'date',
            'hours' => 'integer',
            'base_price' => 'integer',
            'extra_person_price' => 'integer',
            'total_price' => 'integer',
            'status' => OrderStatus::class,
        ];
    }

    // Relationships

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function orderPersons(): HasMany
    {
        return $this->hasMany(OrderPerson::class);
    }

    public function persons(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'order_persons')
            ->withPivot(['price', 'is_primary', 'receipt_url']);
    }
}

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
        'extra_student_price',
        'total_price',
        'status',
        'payment_id',
    ];

    protected function casts(): array
    {
        return [
            'lesson_date' => 'date',
            'hours' => 'integer',
            'base_price' => 'integer',
            'extra_student_price' => 'integer',
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

    public function orderStudents(): HasMany
    {
        return $this->hasMany(OrderStudent::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'order_students')
            ->withPivot(['price', 'is_primary', 'receipt_url']);
    }
}

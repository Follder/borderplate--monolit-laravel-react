<?php

namespace App\Models;

use App\Enums\SkillLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'phone',
        'birth_date',
        'skill_level',
        'lessons_count',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'skill_level' => SkillLevel::class,
            'lessons_count' => 'integer',
        ];
    }

    // Relationships

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderStudents(): HasMany
    {
        return $this->hasMany(OrderStudent::class);
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_students');
    }

    // Attributes

    public function getAgeAttribute(): int
    {
        /** @var \Illuminate\Support\Carbon $birthDate */
        $birthDate = $this->birth_date;

        return $birthDate->age;
    }

    /**
     * Вікова група: children (3-6), juniors (7-12), adults (13+)
     */
    public function getAgeGroupAttribute(): string
    {
        $age = $this->age;

        if ($age <= 6) {
            return 'children';
        }

        if ($age <= 12) {
            return 'juniors';
        }

        return 'adults';
    }
}

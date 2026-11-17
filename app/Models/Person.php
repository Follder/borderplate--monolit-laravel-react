<?php

namespace App\Models;

use App\Enums\Sex;
use App\Enums\SkillLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property string $first_name
 * @property string|null $phone
 * @property Carbon $birth_date
 * @property SkillLevel $skill_level
 * @property Sex $sex
 * @property int $lessons_count
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read int $age
 * @property-read string $age_group
 */
class Person extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'phone',
        'birth_date',
        'skill_level',
        'sex',
        'lessons_count',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'skill_level' => SkillLevel::class,
            'sex' => Sex::class,
            'lessons_count' => 'integer',
        ];
    }

    // Relationships

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderPersons(): HasMany
    {
        return $this->hasMany(OrderPerson::class);
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_persons');
    }

    // Attributes

    public function getAgeAttribute(): int
    {
        return $this->birth_date->age;
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

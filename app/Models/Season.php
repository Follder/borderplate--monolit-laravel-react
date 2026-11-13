<?php

namespace App\Models;

use App\Enums\SeasonType;
use Illuminate\Database\Eloquent\Model;

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
}

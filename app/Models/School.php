<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'npsn',
        'street',
        'village',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'slug' => 'string',
        ];
    }
}

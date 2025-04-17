<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
        'district_id',
        'form_of_education_id',
    ];

    protected function casts(): array
    {
        return [
            'slug' => 'string',
        ];
    }

    // TODO Scope
    public function scopeFilterByNpsn(Builder $query, $npsn): Builder
    {
        return $query->where('npsn', $npsn);
    }
}

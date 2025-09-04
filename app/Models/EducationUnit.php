<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class EducationUnit extends Model
{
    protected $fillable = [
        'code',
        'name',
    ];

    #[Scope]
    protected function filterByCode(Builder $query, $code): Builder
    {
        return $query->where('code', $code);
    }
}

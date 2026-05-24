<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name', 'phone', 'email', 'region', 'province_code', 'city_code', 'district_code',
    'topic', 'status', 'locale', 'ip',
])]
class SupportServiceRequest extends Model
{
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }
}

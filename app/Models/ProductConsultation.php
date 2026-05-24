<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'product_id', 'product_name', 'name', 'phone', 'email', 'city', 'topic', 'remark', 'status', 'locale', 'ip',
])]
class ProductConsultation extends Model
{
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

<?php

namespace App\Models;

use App\Models\Concerns\HasLocale;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'category', 'image', 'sort_order', 'is_active', 'locale'])]
class CompanyHonor extends Model
{
    use HasLocale;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function categoryLabel(): string
    {
        return match ($this->category) {
            'qualification' => '企业资质',
            'award' => '奖牌荣誉',
            'certificate' => '体系认证',
            'patent' => '专利/软著',
            default => '荣誉认证',
        };
    }
}

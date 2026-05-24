<?php

namespace App\Models;

use App\Models\Concerns\HasLocale;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'label', 'value', 'unit', 'description', 'sort_order', 'is_home_show', 'is_active', 'locale',
])]
class SiteStatistic extends Model
{
    use HasLocale;

    protected function casts(): array
    {
        return [
            'is_home_show' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeHome(Builder $query): Builder
    {
        return $query->where('is_home_show', true);
    }

    public function displayValue(): string
    {
        $value = trim((string) $this->value);
        $unit = trim((string) ($this->unit ?? ''));

        if ($unit === '') {
            return $value;
        }

        if ($value === '') {
            return $unit;
        }

        return str_ends_with($value, $unit) ? $value : $value.$unit;
    }

    public static function ensureDefaults(string $locale = 'zh-cn'): void
    {
        $stats = [
            ['value' => '12+', 'label' => '合作伙伴', 'unit' => ''],
            ['value' => '7+', 'label' => '项目案例', 'unit' => ''],
            ['value' => '110+', 'label' => '覆盖城市', 'unit' => ''],
            ['value' => '91.3%', 'label' => '项目落地率', 'unit' => ''],
        ];

        foreach ($stats as $i => $row) {
            static::query()->updateOrCreate(
                ['label' => $row['label'], 'locale' => $locale],
                array_merge($row, [
                    'sort_order' => $i,
                    'is_home_show' => true,
                    'is_active' => true,
                    'locale' => $locale,
                ])
            );
        }
    }
}

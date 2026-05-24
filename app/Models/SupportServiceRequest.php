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
    public const STATUS_PENDING = 'pending';

    public const STATUS_CONTACTED = 'contacted';

    public const STATUS_CLOSED = 'closed';

    /** @return array<string, string> */
    public static function statusLabels(): array
    {
        return [
            self::STATUS_PENDING => '待处理',
            self::STATUS_CONTACTED => '已联系',
            self::STATUS_CLOSED => '已关闭',
        ];
    }

    public static function statusLabel(?string $status): string
    {
        return static::statusLabels()[$status ?? ''] ?? ($status ?: '—');
    }

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }
}

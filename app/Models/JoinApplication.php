<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'position_id', 'position_title', 'name', 'phone', 'email', 'city',
    'resume_path', 'resume_original_name', 'message', 'status', 'admin_note', 'ip', 'locale',
])]
class JoinApplication extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_CONTACTED = 'contacted';

    public const STATUS_INTERVIEW = 'interview';

    public const STATUS_HIRED = 'hired';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_CLOSED = 'closed';

    /** @return array<string, string> */
    public static function statusLabels(): array
    {
        return [
            self::STATUS_PENDING => '待处理',
            self::STATUS_CONTACTED => '已联系',
            self::STATUS_INTERVIEW => '面试中',
            self::STATUS_HIRED => '已录用',
            self::STATUS_REJECTED => '未通过',
            self::STATUS_CLOSED => '已关闭',
        ];
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(JoinPosition::class, 'position_id');
    }

    public function statusLabel(): string
    {
        return static::statusLabels()[$this->status] ?? $this->status;
    }

    public function resumeUrl(): ?string
    {
        if (blank($this->resume_path)) {
            return null;
        }

        return Storage::disk('public')->url($this->resume_path);
    }
}

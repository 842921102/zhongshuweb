<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'is_enabled',
    'use_for_uploads',
    'secret_id',
    'secret_key',
    'region',
    'bucket',
    'app_id',
    'path_prefix',
    'cdn_domain',
    'last_tested_at',
    'last_test_status',
    'last_test_message',
])]
class BusinessCosSetting extends Model
{
    public const TEST_OK = 'success';

    public const TEST_FAIL = 'failed';

    /** @var array<string, string> */
    public const REGIONS = [
        'ap-guangzhou' => '广州 (ap-guangzhou)',
        'ap-shanghai' => '上海 (ap-shanghai)',
        'ap-beijing' => '北京 (ap-beijing)',
        'ap-chengdu' => '成都 (ap-chengdu)',
        'ap-chongqing' => '重庆 (ap-chongqing)',
        'ap-nanjing' => '南京 (ap-nanjing)',
        'ap-hongkong' => '香港 (ap-hongkong)',
        'ap-singapore' => '新加坡 (ap-singapore)',
    ];

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
            'use_for_uploads' => 'boolean',
            'secret_id' => 'encrypted',
            'secret_key' => 'encrypted',
            'last_tested_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saved(fn () => static::flushResolvedInstance());
        static::deleted(fn () => static::flushResolvedInstance());
    }

    private static ?self $resolvedInstance = null;

    public static function instance(): self
    {
        if (static::$resolvedInstance !== null) {
            return static::$resolvedInstance;
        }

        return static::$resolvedInstance = static::query()->firstOrCreate([], [
            'region' => 'ap-guangzhou',
            'path_prefix' => 'uploads',
        ]);
    }

    public static function flushResolvedInstance(): void
    {
        static::$resolvedInstance = null;
    }

    /**
     * @return list<string>
     */
    public function configurationErrors(): array
    {
        $errors = [];
        $bucket = trim((string) $this->bucket);
        $appId = trim((string) $this->app_id);

        if ($appId !== '' && str_starts_with(strtoupper($appId), 'AKID')) {
            $errors[] = 'AppId 不能填写 SecretId。请到腾讯云控制台「账号信息」查看纯数字 AppId（例如 1256529290），SecretId 只填在上方密钥栏。';
        }

        if ($appId !== '' && ! ctype_digit($appId)) {
            $errors[] = 'AppId 必须为纯数字。';
        }

        if (! $this->hasEmbeddedAppIdInBucket() && ! ctype_digit($appId)) {
            $errors[] = '请填写数字 AppId，或在 Bucket 一栏直接填写完整桶名（例如 fanfou-1256529290）。';
        }

        if (str_contains(strtoupper($bucket), 'AKID')) {
            $errors[] = 'Bucket 名称中不能包含 SecretId，请只填写存储桶名称。';
        }

        $full = $this->fullBucketName();
        if ($full !== '' && str_contains(strtoupper($full), 'AKID')) {
            $errors[] = '解析后的完整桶名异常（含 AKID），请检查 Bucket 与 AppId 是否填反。';
        }

        return $errors;
    }

    public function hasEmbeddedAppIdInBucket(): bool
    {
        $bucket = trim((string) $this->bucket);

        return (bool) preg_match('/-\d{6,}$/', $bucket);
    }

    public function isConfigured(): bool
    {
        if (! filled($this->secret_id) || ! filled($this->secret_key) || ! filled($this->region) || ! filled($this->bucket)) {
            return false;
        }

        if ($this->configurationErrors() !== []) {
            return false;
        }

        return filled($this->fullBucketName());
    }

    public function fullBucketName(): string
    {
        $bucket = trim((string) $this->bucket);

        if ($bucket === '') {
            return '';
        }

        // 已是完整桶名：name-1256529290
        if ($this->hasEmbeddedAppIdInBucket()) {
            return $bucket;
        }

        $appId = trim((string) $this->app_id);

        if ($appId !== '' && ctype_digit($appId)) {
            return "{$bucket}-{$appId}";
        }

        return $bucket;
    }

    public function endpoint(): string
    {
        return 'https://cos.'.trim((string) $this->region).'.myqcloud.com';
    }

    public function publicBaseUrl(): ?string
    {
        $cdn = trim((string) $this->cdn_domain);
        if ($cdn !== '') {
            return rtrim($cdn, '/');
        }

        $bucket = $this->fullBucketName();
        $region = trim((string) $this->region);

        if ($bucket === '' || $region === '') {
            return null;
        }

        return "https://{$bucket}.cos.{$region}.myqcloud.com";
    }

    public function normalizedPathPrefix(): string
    {
        return trim(trim((string) $this->path_prefix), '/');
    }
}

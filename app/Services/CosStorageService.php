<?php

namespace App\Services;

use App\Models\BusinessCosSetting;
use Illuminate\Support\Facades\Storage;
use Throwable;

class CosStorageService
{
    public function applyDiskConfig(?BusinessCosSetting $settings = null): void
    {
        $settings ??= BusinessCosSetting::instance();

        if (! $settings->isConfigured()) {
            return;
        }

        $disk = $this->buildDiskConfig($settings);

        config(['filesystems.disks.cos' => $disk]);

        if ($settings->is_enabled && $settings->use_for_uploads) {
            config(['filesystems.disks.cos_uploads' => $disk]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function buildDiskConfig(BusinessCosSetting $settings): array
    {
        return [
            'driver' => 's3',
            'key' => $settings->secret_id,
            'secret' => $settings->secret_key,
            'region' => $settings->region,
            'bucket' => $settings->fullBucketName(),
            'url' => $settings->publicBaseUrl(),
            'endpoint' => $settings->endpoint(),
            'use_path_style_endpoint' => false,
            'visibility' => 'public',
            'root' => $settings->normalizedPathPrefix(),
            'throw' => true,
        ];
    }

    /**
     * @return array{ok: bool, message: string}
     */
    public function testConnection(?BusinessCosSetting $settings = null): array
    {
        $settings ??= BusinessCosSetting::instance();

        $configErrors = $settings->configurationErrors();
        if ($configErrors !== []) {
            return [
                'ok' => false,
                'message' => implode(' ', $configErrors),
            ];
        }

        if (! $settings->isConfigured()) {
            return [
                'ok' => false,
                'message' => '请先填写 SecretId、SecretKey、Bucket、地域；AppId 为纯数字，或 Bucket 直接填完整名称（如 fanfou-1256529290）。',
            ];
        }

        $this->applyDiskConfig($settings);

        $path = '_healthcheck/'.date('Ymd').'/ping-'.time().'.txt';

        try {
            Storage::disk('cos')->put($path, 'cos-ok-'.now()->toIso8601String());

            if (! Storage::disk('cos')->exists($path)) {
                throw new \RuntimeException('文件上传后无法读取，请检查 Bucket 权限与地域配置。');
            }

            Storage::disk('cos')->delete($path);

            return [
                'ok' => true,
                'message' => '连接成功：桶 '.$settings->fullBucketName().'，已上传并删除测试文件 '.$path,
            ];
        } catch (Throwable $e) {
            $message = $e->getMessage();

            if (str_contains($message, 'NoSuchBucket')) {
                $message .= '。请确认：① Bucket/AppId 未填反；② 桶名与地域一致（当前解析为「'
                    .$settings->fullBucketName().'」，地域 '.$settings->region.'）；③ 控制台中该桶已创建。';
            }

            return [
                'ok' => false,
                'message' => '连接失败：'.$message,
            ];
        }
    }

    public static function uploadDisk(): string
    {
        $settings = BusinessCosSetting::instance();

        if ($settings->is_enabled && $settings->use_for_uploads && $settings->isConfigured()) {
            return 'cos';
        }

        return 'public';
    }
}

<?php

namespace App\Filament\Resources\BusinessCosSettings\Pages;

use App\Filament\Resources\BusinessCosSettings\BusinessCosSettingResource;
use App\Models\BusinessCosSetting;
use App\Services\CosStorageService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditBusinessCosSetting extends EditRecord
{
    protected static string $resource = BusinessCosSettingResource::class;

    protected static ?string $title = '业务配置 · 腾讯云 COS';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('testConnection')
                ->label('测试连接')
                ->icon('heroicon-o-signal')
                ->color('gray')
                ->action(fn () => $this->runConnectionTest()),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['secret_id'] = '';
        $data['secret_key'] = '';

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $bucket = trim((string) ($data['bucket'] ?? ''));
        $appId = trim((string) ($data['app_id'] ?? ''));

        if (preg_match('/-\d{6,}$/', $bucket)) {
            $data['app_id'] = null;
        } elseif (str_starts_with(strtoupper($appId), 'AKID')) {
            $data['app_id'] = null;
        }

        return $data;
    }

    protected function afterSave(): void
    {
        app(CosStorageService::class)->applyDiskConfig($this->record);
    }

    protected function runConnectionTest(): void
    {
        $settings = $this->buildSettingsForTest();

        $result = app(CosStorageService::class)->testConnection($settings);

        $this->record->forceFill([
            'last_tested_at' => now(),
            'last_test_status' => $result['ok'] ? BusinessCosSetting::TEST_OK : BusinessCosSetting::TEST_FAIL,
            'last_test_message' => $result['message'],
        ])->save();

        $this->record->refresh();
        $this->fillForm();

        if ($result['ok']) {
            Notification::make()
                ->title('COS 连接测试成功')
                ->body($result['message'])
                ->success()
                ->send();

            return;
        }

        Notification::make()
            ->title('COS 连接测试失败')
            ->body($result['message'])
            ->danger()
            ->send();
    }

    protected function buildSettingsForTest(): BusinessCosSetting
    {
        $state = $this->form->getState();
        $settings = $this->getRecord()->replicate();
        $settings->exists = true;
        $settings->id = $this->getRecord()->id;
        $settings->fill($state);

        if (blank($state['secret_id'] ?? null)) {
            $settings->secret_id = $this->getRecord()->secret_id;
        }

        if (blank($state['secret_key'] ?? null)) {
            $settings->secret_key = $this->getRecord()->secret_key;
        }

        return $settings;
    }
}

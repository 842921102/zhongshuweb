<?php

namespace App\Filament\Resources\JoinApplications\Pages;

use App\Filament\Resources\JoinApplications\JoinApplicationResource;
use App\Models\JoinApplication;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditJoinApplication extends EditRecord
{
    protected static string $resource = JoinApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download_resume')
                ->label('下载简历')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(fn (JoinApplication $record): ?string => $record->resumeUrl())
                ->openUrlInNewTab()
                ->visible(fn (JoinApplication $record): bool => filled($record->resume_path)),
            \Filament\Actions\DeleteAction::make(),
        ];
    }
}

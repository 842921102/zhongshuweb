<?php

namespace App\Filament\Resources\SupportVideos;

use App\Filament\Resources\SupportVideos\Pages\ManageSupportVideos;
use App\Filament\Resources\SupportVideos\Schemas\SupportVideoForm;
use App\Models\SupportVideo;
use App\Support\Filament\ResourceTableActions;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use UnitEnum;

class SupportVideoResource extends Resource
{
    protected static ?string $model = SupportVideo::class;

    protected static string|UnitEnum|null $navigationGroup = '技术支持';

    protected static ?string $navigationLabel = '教学视频';

    protected static ?string $modelLabel = '教学视频';

    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPlayCircle;

    public static function form(Schema $schema): Schema
    {
        return SupportVideoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')->label('封面')->disk(upload_disk())->height(40),
                TextColumn::make('title')->label('标题')->searchable()->sortable(),
                TextColumn::make('tag')->label('标签')->toggleable(),
                TextColumn::make('duration_label')->label('时长')->toggleable(),
                TextColumn::make('play_count')->label('播放量')->sortable(),
                TextColumn::make('sort_order')->label('排序')->sortable(),
                ToggleColumn::make('is_active')->label('启用'),
            ])
            ->defaultSort('sort_order')
            ->filters([
                TernaryFilter::make('is_active')->label('启用'),
                SelectFilter::make('locale')
                    ->label('语言')
                    ->options(['zh-cn' => '中文', 'en-us' => 'English']),
            ])
            ->recordActions(ResourceTableActions::recordActions(
                configureEdit: fn (EditAction $action) => $action
                    ->fillForm(fn (SupportVideo $record): array => SupportVideoForm::fillFormState($record))
                    ->using(function (SupportVideo $record, array $data): SupportVideo {
                        $record->update(SupportVideoForm::normalizePersistedData($data));

                        return $record;
                    }),
                configureReplicate: fn (ReplicateAction $action) => $action
                    ->mutateRecordDataUsing(function (array $data): array {
                        $data = ResourceTableActions::mutateReplicaData($data);
                        $data['play_count'] = 0;

                        return $data;
                    }),
            ))
            ->toolbarActions(ResourceTableActions::toolbarActions());
    }

    public static function getPages(): array
    {
        return ['index' => ManageSupportVideos::route('/')];
    }
}

<?php

namespace App\Filament\Resources\JoinPageSettings;

use App\Filament\Resources\JoinPageSettings\Pages\EditJoinPageSetting;
use App\Filament\Resources\JoinPageSettings\Pages\ListJoinPageSettings;
use App\Filament\Resources\JoinPageSettings\Schemas\JoinPageSettingForm;
use App\Models\JoinPageSetting;
use App\Support\Filament\ResourceTableActions;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class JoinPageSettingResource extends Resource
{
    protected static ?string $model = JoinPageSetting::class;

    protected static ?string $recordTitleAttribute = 'meta_title';

    protected static string|UnitEnum|null $navigationGroup = '加入我们';

    protected static ?string $navigationLabel = '页面设置';

    protected static ?string $modelLabel = '加入我们页';

    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    public static function form(Schema $schema): Schema
    {
        return JoinPageSettingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            \Filament\Tables\Columns\TextColumn::make('meta_title')->label('标题'),
        ])
            ->recordActions(ResourceTableActions::recordActions(editLabel: '编辑'))
            ->toolbarActions(ResourceTableActions::toolbarActions());
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJoinPageSettings::route('/'),
            'edit' => EditJoinPageSetting::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}

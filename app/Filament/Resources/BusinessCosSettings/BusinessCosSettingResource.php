<?php

namespace App\Filament\Resources\BusinessCosSettings;

use App\Filament\Resources\BusinessCosSettings\Pages\EditBusinessCosSetting;
use App\Filament\Resources\BusinessCosSettings\Pages\ListBusinessCosSettings;
use App\Filament\Resources\BusinessCosSettings\Schemas\BusinessCosSettingForm;
use App\Models\BusinessCosSetting;
use App\Support\Filament\ResourceTableActions;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class BusinessCosSettingResource extends Resource
{
    protected static ?string $model = BusinessCosSetting::class;

    protected static ?string $slug = 'business/cos';

    protected static string|UnitEnum|null $navigationGroup = '系统设置';

    protected static ?string $navigationLabel = '业务配置';

    protected static ?string $modelLabel = '对象存储';

    protected static ?string $pluralModelLabel = '业务配置';

    protected static ?int $navigationSort = 8;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCloudArrowUp;

    public static function form(Schema $schema): Schema
    {
        return BusinessCosSettingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            \Filament\Tables\Columns\ToggleColumn::make('is_enabled')->label('已启用'),
            \Filament\Tables\Columns\TextColumn::make('region')->label('地域'),
            \Filament\Tables\Columns\TextColumn::make('bucket')->label('Bucket'),
        ])
            ->recordActions(ResourceTableActions::recordActions(replicate: false, delete: false));
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBusinessCosSettings::route('/'),
            'edit' => EditBusinessCosSetting::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}

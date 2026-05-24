<?php

namespace App\Filament\Resources\SupportPageSettings;

use App\Filament\Resources\SupportPageSettings\Pages\EditSupportPageSetting;
use App\Filament\Resources\SupportPageSettings\Pages\ListSupportPageSettings;
use App\Filament\Resources\SupportPageSettings\Schemas\SupportPageSettingForm;
use App\Models\SupportPageSetting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class SupportPageSettingResource extends Resource
{
    protected static ?string $model = SupportPageSetting::class;

    protected static string|UnitEnum|null $navigationGroup = '技术支持';

    protected static ?string $navigationLabel = '页面设置';

    protected static ?string $modelLabel = '技术支持页面';

    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    public static function form(Schema $schema): Schema
    {
        return SupportPageSettingForm::configure($schema);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSupportPageSettings::route('/'),
            'edit' => EditSupportPageSetting::route('/{record}/edit'),
        ];
    }
}

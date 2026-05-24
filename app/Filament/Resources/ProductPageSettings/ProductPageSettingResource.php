<?php

namespace App\Filament\Resources\ProductPageSettings;

use App\Filament\Resources\ProductPageSettings\Pages\EditProductPageSetting;
use App\Filament\Resources\ProductPageSettings\Pages\ListProductPageSettings;
use App\Filament\Resources\ProductPageSettings\Schemas\ProductPageSettingForm;
use App\Models\ProductPageSetting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ProductPageSettingResource extends Resource
{
    protected static ?string $model = ProductPageSetting::class;

    protected static string|UnitEnum|null $navigationGroup = '产品管理';

    protected static ?string $navigationLabel = '产品中心页面';

    protected static ?string $modelLabel = '产品中心';

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAdjustmentsHorizontal;

    public static function form(Schema $schema): Schema
    {
        return ProductPageSettingForm::configure($schema);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductPageSettings::route('/'),
            'edit' => EditProductPageSetting::route('/{record}/edit'),
        ];
    }
}

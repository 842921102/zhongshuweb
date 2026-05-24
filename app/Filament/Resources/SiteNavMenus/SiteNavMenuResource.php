<?php

namespace App\Filament\Resources\SiteNavMenus;

use App\Filament\Resources\SiteNavMenus\Pages\CreateSiteNavMenu;
use App\Filament\Resources\SiteNavMenus\Pages\EditSiteNavMenu;
use App\Filament\Resources\SiteNavMenus\Pages\ListSiteNavMenus;
use App\Filament\Resources\SiteNavMenus\Schemas\SiteNavMenuForm;
use App\Filament\Resources\SiteNavMenus\Tables\SiteNavMenusTable;
use App\Models\SiteNavMenu;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SiteNavMenuResource extends Resource
{
    protected static ?string $model = SiteNavMenu::class;

    protected static ?string $recordTitleAttribute = 'label';

    protected static string|UnitEnum|null $navigationGroup = '官网管理';

    protected static ?string $navigationLabel = '菜单管理';

    protected static ?string $modelLabel = '菜单项';

    protected static ?string $pluralModelLabel = '菜单管理';

    protected static ?int $navigationSort = 5;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBars3;

    public static function form(Schema $schema): Schema
    {
        return SiteNavMenuForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SiteNavMenusTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSiteNavMenus::route('/'),
            'create' => CreateSiteNavMenu::route('/create'),
            'edit' => EditSiteNavMenu::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->whereNull('parent_id');
    }
}

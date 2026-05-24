<?php

namespace App\Filament\Resources\SiteFooterLinks;

use App\Filament\Resources\SiteFooterLinks\Pages\CreateSiteFooterLink;
use App\Filament\Resources\SiteFooterLinks\Pages\EditSiteFooterLink;
use App\Filament\Resources\SiteFooterLinks\Pages\ListSiteFooterLinks;
use App\Filament\Resources\SiteFooterLinks\Schemas\SiteFooterLinkForm;
use App\Filament\Resources\SiteFooterLinks\Tables\SiteFooterLinksTable;
use App\Models\SiteFooterLink;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SiteFooterLinkResource extends Resource
{
    protected static ?string $model = SiteFooterLink::class;

    protected static string|UnitEnum|null $navigationGroup = '系统设置';

    protected static ?string $navigationLabel = '页脚链接';

    protected static ?string $modelLabel = '页脚链接';

    protected static ?string $pluralModelLabel = '页脚链接';

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLink;

    public static function form(Schema $schema): Schema
    {
        return SiteFooterLinkForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SiteFooterLinksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSiteFooterLinks::route('/'),
            'create' => CreateSiteFooterLink::route('/create'),
            'edit' => EditSiteFooterLink::route('/{record}/edit'),
        ];
    }
}

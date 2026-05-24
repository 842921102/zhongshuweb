<?php

namespace App\Filament\Resources\SitePartners;

use App\Filament\Resources\SitePartners\Pages\CreateSitePartner;
use App\Filament\Resources\SitePartners\Pages\EditSitePartner;
use App\Filament\Resources\SitePartners\Pages\ListSitePartners;
use App\Filament\Resources\SitePartners\Schemas\SitePartnerForm;
use App\Filament\Resources\SitePartners\Tables\SitePartnersTable;
use App\Models\SitePartner;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SitePartnerResource extends Resource
{
    protected static ?string $model = SitePartner::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|UnitEnum|null $navigationGroup = '官网管理';

    protected static ?string $navigationLabel = '合作伙伴';

    protected static ?string $modelLabel = '合作伙伴';

    protected static ?string $pluralModelLabel = '合作伙伴';

    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    public static function form(Schema $schema): Schema
    {
        return SitePartnerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SitePartnersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSitePartners::route('/'),
            'create' => CreateSitePartner::route('/create'),
            'edit' => EditSitePartner::route('/{record}/edit'),
        ];
    }
}

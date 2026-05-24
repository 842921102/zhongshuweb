<?php

namespace App\Filament\Resources\HomeSections;

use App\Filament\Resources\HomeSections\Pages\CreateHomeSection;
use App\Filament\Resources\HomeSections\Pages\EditHomeSection;
use App\Filament\Resources\HomeSections\Pages\ListHomeSections;
use App\Filament\Resources\HomeSections\Schemas\HomeSectionForm;
use App\Filament\Resources\HomeSections\Tables\HomeSectionsTable;
use App\Models\HomeSection;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class HomeSectionResource extends Resource
{
    protected static ?string $model = HomeSection::class;

    protected static ?string $recordTitleAttribute = 'section_name';

    protected static string|UnitEnum|null $navigationGroup = '官网管理';

    protected static ?string $navigationLabel = '首页模块';

    protected static ?string $modelLabel = '首页模块';

    protected static ?string $pluralModelLabel = '首页模块';

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    public static function form(Schema $schema): Schema
    {
        return HomeSectionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HomeSectionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHomeSections::route('/'),
            'create' => CreateHomeSection::route('/create'),
            'edit' => EditHomeSection::route('/{record}/edit'),
        ];
    }
}

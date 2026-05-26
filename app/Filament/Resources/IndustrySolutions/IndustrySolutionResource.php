<?php

namespace App\Filament\Resources\IndustrySolutions;

use App\Filament\Resources\IndustrySolutions\Pages\CreateIndustrySolution;
use App\Filament\Resources\IndustrySolutions\Pages\EditIndustrySolution;
use App\Filament\Resources\IndustrySolutions\Pages\ListIndustrySolutions;
use App\Filament\Resources\IndustrySolutions\Schemas\IndustrySolutionForm;
use App\Filament\Resources\IndustrySolutions\Tables\IndustrySolutionsTable;
use App\Models\IndustrySolution;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class IndustrySolutionResource extends Resource
{
    protected static ?string $model = IndustrySolution::class;

    protected static string|UnitEnum|null $navigationGroup = '解决方案';

    protected static ?string $navigationLabel = '方案列表';

    protected static ?string $modelLabel = '解决方案';

    protected static ?string $pluralModelLabel = '解决方案列表';

    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    public static function form(Schema $schema): Schema
    {
        return IndustrySolutionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IndustrySolutionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListIndustrySolutions::route('/'),
            'create' => CreateIndustrySolution::route('/create'),
            'edit' => EditIndustrySolution::route('/{record}/edit'),
        ];
    }
}

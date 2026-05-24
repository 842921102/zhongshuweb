<?php

namespace App\Filament\Resources\CaseStudyCategories;

use App\Filament\Resources\CaseStudyCategories\Pages\CreateCaseStudyCategory;
use App\Filament\Resources\CaseStudyCategories\Pages\EditCaseStudyCategory;
use App\Filament\Resources\CaseStudyCategories\Pages\ListCaseStudyCategories;
use App\Filament\Resources\CaseStudyCategories\Schemas\CaseStudyCategoryForm;
use App\Filament\Resources\CaseStudyCategories\Tables\CaseStudyCategoriesTable;
use App\Models\CaseStudyCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CaseStudyCategoryResource extends Resource
{
    protected static ?string $model = CaseStudyCategory::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|UnitEnum|null $navigationGroup = '案例管理';

    protected static ?string $navigationLabel = '案例分类';

    protected static ?string $modelLabel = '案例分类';

    protected static ?string $pluralModelLabel = '案例分类';

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    public static function form(Schema $schema): Schema
    {
        return CaseStudyCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CaseStudyCategoriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCaseStudyCategories::route('/'),
            'create' => CreateCaseStudyCategory::route('/create'),
            'edit' => EditCaseStudyCategory::route('/{record}/edit'),
        ];
    }
}

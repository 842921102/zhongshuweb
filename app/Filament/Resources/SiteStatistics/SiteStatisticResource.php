<?php

namespace App\Filament\Resources\SiteStatistics;

use App\Filament\Resources\SiteStatistics\Pages\CreateSiteStatistic;
use App\Filament\Resources\SiteStatistics\Pages\EditSiteStatistic;
use App\Filament\Resources\SiteStatistics\Pages\ListSiteStatistics;
use App\Filament\Resources\SiteStatistics\Schemas\SiteStatisticForm;
use App\Filament\Resources\SiteStatistics\Tables\SiteStatisticsTable;
use App\Models\SiteStatistic;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SiteStatisticResource extends Resource
{
    protected static ?string $model = SiteStatistic::class;

    protected static ?string $recordTitleAttribute = 'label';

    protected static string|UnitEnum|null $navigationGroup = '官网管理';

    protected static ?string $navigationLabel = '数据指标';

    protected static ?string $modelLabel = '数据指标';

    protected static ?string $pluralModelLabel = '数据指标';

    protected static ?int $navigationSort = 4;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    public static function form(Schema $schema): Schema
    {
        return SiteStatisticForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SiteStatisticsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSiteStatistics::route('/'),
            'create' => CreateSiteStatistic::route('/create'),
            'edit' => EditSiteStatistic::route('/{record}/edit'),
        ];
    }
}

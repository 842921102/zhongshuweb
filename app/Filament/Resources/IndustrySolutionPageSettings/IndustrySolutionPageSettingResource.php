<?php

namespace App\Filament\Resources\IndustrySolutionPageSettings;

use App\Filament\Resources\IndustrySolutionPageSettings\Pages\EditIndustrySolutionPageSetting;
use App\Filament\Resources\IndustrySolutionPageSettings\Pages\ListIndustrySolutionPageSettings;
use App\Filament\Resources\IndustrySolutionPageSettings\Schemas\IndustrySolutionPageSettingForm;
use App\Models\IndustrySolutionPageSetting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class IndustrySolutionPageSettingResource extends Resource
{
    protected static ?string $model = IndustrySolutionPageSetting::class;

    protected static ?string $recordTitleAttribute = 'page_title';

    protected static string|UnitEnum|null $navigationGroup = '解决方案';

    protected static ?string $navigationLabel = '列表页设置';

    protected static ?string $modelLabel = '解决方案列表页';

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    public static function form(Schema $schema): Schema
    {
        return IndustrySolutionPageSettingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('page_title')->label('页面标题'),
                \Filament\Tables\Columns\TextColumn::make('locale')->label('语言')->badge(),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make()->label('编辑'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListIndustrySolutionPageSettings::route('/'),
            'edit' => EditIndustrySolutionPageSetting::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}

<?php

namespace App\Filament\Resources\CasePageSettings;

use App\Filament\Resources\CasePageSettings\Pages\EditCasePageSetting;
use App\Filament\Resources\CasePageSettings\Pages\ListCasePageSettings;
use App\Filament\Resources\CasePageSettings\Schemas\CasePageSettingForm;
use App\Models\CasePageSetting;
use App\Support\Filament\ResourceTableActions;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CasePageSettingResource extends Resource
{
    protected static ?string $model = CasePageSetting::class;

    protected static ?string $recordTitleAttribute = 'page_title';

    protected static string|UnitEnum|null $navigationGroup = '案例管理';

    protected static ?string $navigationLabel = '案例页设置';

    protected static ?string $modelLabel = '案例页';

    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    public static function form(Schema $schema): Schema
    {
        return CasePageSettingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('page_title')->label('页面标题'),
                \Filament\Tables\Columns\TextColumn::make('locale')->label('语言')->badge(),
            ])
            ->recordActions(ResourceTableActions::recordActions(editLabel: '编辑'))
            ->toolbarActions(ResourceTableActions::toolbarActions());
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCasePageSettings::route('/'),
            'edit' => EditCasePageSetting::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}

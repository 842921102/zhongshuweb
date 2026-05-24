<?php

namespace App\Filament\Resources\CompanyPageSettings;

use App\Filament\Resources\CompanyPageSettings\Pages\EditCompanyPageSetting;
use App\Filament\Resources\CompanyPageSettings\Pages\ListCompanyPageSettings;
use App\Filament\Resources\CompanyPageSettings\Schemas\CompanyPageSettingForm;
use App\Models\CompanyPageSetting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CompanyPageSettingResource extends Resource
{
    protected static ?string $model = CompanyPageSetting::class;

    protected static ?string $recordTitleAttribute = 'intro_title';

    protected static string|UnitEnum|null $navigationGroup = '关于我们';

    protected static ?string $navigationLabel = '页面设置（总览）';

    protected static ?string $modelLabel = '关于我们页';

    protected static ?string $pluralModelLabel = '关于我们页面';

    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    public static function form(Schema $schema): Schema
    {
        return CompanyPageSettingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('intro_title')->label('标题'),
                \Filament\Tables\Columns\TextColumn::make('locale')->label('语言')->badge(),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make()->label('编辑'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCompanyPageSettings::route('/'),
            'edit' => EditCompanyPageSetting::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}

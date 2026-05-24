<?php

namespace App\Filament\Resources\NewsPageSettings;

use App\Filament\Resources\NewsPageSettings\Pages\EditNewsPageSetting;
use App\Filament\Resources\NewsPageSettings\Pages\ListNewsPageSettings;
use App\Filament\Resources\NewsPageSettings\Schemas\NewsPageSettingForm;
use App\Models\NewsPageSetting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class NewsPageSettingResource extends Resource
{
    protected static ?string $model = NewsPageSetting::class;

    protected static ?string $recordTitleAttribute = 'meta_title';

    protected static string|UnitEnum|null $navigationGroup = '新闻管理';

    protected static ?string $navigationLabel = '新闻页设置';

    protected static ?string $modelLabel = '新闻页';

    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    public static function form(Schema $schema): Schema
    {
        return NewsPageSettingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('meta_title')->label('SEO 标题'),
                \Filament\Tables\Columns\TextColumn::make('locale')->label('语言')->badge(),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make()->label('编辑'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNewsPageSettings::route('/'),
            'edit' => EditNewsPageSetting::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}

<?php

namespace App\Filament\Resources\SupportDocuments;

use App\Filament\Resources\SupportDocuments\Pages\ManageSupportDocuments;
use App\Filament\Resources\SupportDocuments\Schemas\SupportDocumentForm;
use App\Models\SupportDocument;
use App\Support\Filament\ResourceTableActions;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use UnitEnum;

class SupportDocumentResource extends Resource
{
    protected static ?string $model = SupportDocument::class;

    protected static string|UnitEnum|null $navigationGroup = '技术支持';

    protected static ?string $navigationLabel = 'PDF 文档';

    protected static ?string $modelLabel = '技术文档';

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentArrowDown;

    public static function form(Schema $schema): Schema
    {
        return SupportDocumentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('名称')->searchable()->sortable(),
                TextColumn::make('category')->label('分类')->sortable(),
                TextColumn::make('version')->label('版本')->toggleable(),
                TextColumn::make('published_label')->label('月份')->toggleable(),
                TextColumn::make('file_size_label')->label('大小')->toggleable(),
                TextColumn::make('sort_order')->label('排序')->sortable(),
                ToggleColumn::make('is_active')->label('启用'),
            ])
            ->defaultSort('sort_order')
            ->filters([
                SelectFilter::make('category')
                    ->label('分类')
                    ->options(fn (): array => SupportDocumentForm::categoryOptions()),
                TernaryFilter::make('is_active')->label('启用'),
                SelectFilter::make('locale')
                    ->label('语言')
                    ->options(['zh-cn' => '中文', 'en-us' => 'English']),
            ])
            ->recordActions(ResourceTableActions::recordActions())
            ->toolbarActions(ResourceTableActions::toolbarActions());
    }

    public static function getPages(): array
    {
        return ['index' => ManageSupportDocuments::route('/')];
    }
}

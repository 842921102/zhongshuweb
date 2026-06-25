<?php

namespace App\Filament\Resources\Products\Tables;

use App\Models\Product;
use App\Support\Filament\ResourceTableActions;
use Filament\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('封面')
                    ->disk(upload_disk())
                    ->imageHeight(40)
                    ->imageWidth(40)
                    ->defaultImageUrl(fn () => 'https://ui-avatars.com/api/?name=P&background=00A85A&color=fff&size=80'),
                TextColumn::make('name')
                    ->label('产品名称')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Product $record): ?string => filled($record->subtitle)
                        ? Str::limit($record->subtitle, 42)
                        : null),
                SelectColumn::make('category_id')
                    ->label('分类')
                    ->optionsRelationship(
                        name: 'category',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => $query
                            ->with('parent')
                            ->orderBy('parent_id')
                            ->orderBy('sort_order'),
                    )
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->parent_id
                        ? ($record->parent?->name.' / '.$record->name)
                        : $record->name)
                    ->searchableOptions()
                    ->preloadOptions(),
                TextColumn::make('model_no')
                    ->label('型号')
                    ->toggleable(),
                TextColumn::make('slug')
                    ->label('别名')
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('sort_order')
                    ->label('排序')
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->label('上架'),
                ToggleColumn::make('is_home_show')
                    ->label('首页'),
                ToggleColumn::make('is_home_featured')
                    ->label('首页精选'),
            ])
            ->filters([
                TernaryFilter::make('is_active')->label('上架状态'),
                SelectFilter::make('category_id')
                    ->label('分类')
                    ->relationship('category', 'name'),
            ])
            ->recordActions(ResourceTableActions::recordActions(prepend: [
                Action::make('preview')
                    ->label('预览')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Product $record): string => $record->url())
                    ->openUrlInNewTab()
                    ->visible(fn (Product $record): bool => blank($record->detail_url) || $record->detail_url === '#'),
            ]))
            ->toolbarActions(ResourceTableActions::toolbarActions());
    }
}

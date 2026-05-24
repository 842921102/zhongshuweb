<?php

namespace App\Filament\Resources\Products\Tables;

use App\Models\Product;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('封面')
                    ->disk('public')
                    ->square()
                    ->defaultImageUrl(fn () => 'https://ui-avatars.com/api/?name=P&background=00A85A&color=fff'),
                TextColumn::make('name')
                    ->label('产品名称')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Product $record): ?string => $record->subtitle),
                TextColumn::make('category.name')
                    ->label('分类')
                    ->description(fn (Product $record): ?string => $record->category?->parent?->name),
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
                IconColumn::make('is_active')
                    ->label('上架')
                    ->boolean(),
                IconColumn::make('is_home_show')
                    ->label('首页')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')->label('上架状态'),
                SelectFilter::make('category_id')
                    ->label('分类')
                    ->relationship('category', 'name'),
            ])
            ->recordActions([
                Action::make('preview')
                    ->label('预览')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Product $record): string => $record->url())
                    ->openUrlInNewTab()
                    ->visible(fn (Product $record): bool => blank($record->detail_url) || $record->detail_url === '#'),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Categories\Tables;

use App\Models\Category;
use App\Support\Filament\ResourceTableActions;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('分类名称')
                    ->searchable()
                    ->formatStateUsing(function (string $state, Category $record): HtmlString|string {
                        if ($record->isRoot()) {
                            return $state;
                        }

                        return new HtmlString(
                            '<span class="category-tree-child">'
                            .'<span class="category-tree-glyph" aria-hidden="true">└</span>'
                            .e($state)
                            .'</span>'
                        );
                    })
                    ->html()
                    ->icon(fn (Category $record): Heroicon => $record->isRoot()
                        ? Heroicon::OutlinedFolder
                        : Heroicon::OutlinedTag)
                    ->weight(fn (Category $record): FontWeight => $record->isRoot()
                        ? FontWeight::SemiBold
                        : FontWeight::Normal)
                    ->description(fn (Category $record): ?string => match (true) {
                        $record->isRoot() && $record->children_count > 0 => "包含 {$record->children_count} 个子分类",
                        $record->isRoot() => '一级分类',
                        default => '二级分类',
                    }),
                TextColumn::make('slug')
                    ->label('别名')
                    ->badge()
                    ->color(fn (Category $record): string => $record->isRoot() ? 'gray' : 'primary'),
                TextColumn::make('products_count')
                    ->label('产品数'),
                TextColumn::make('sort_order')
                    ->label('排序'),
                ToggleColumn::make('is_station_tab')
                    ->label('首页 Tab')
                    ->disabled(fn (Category $record): bool => $record->parent_id === null)
                    ->tooltip(fn (Category $record): ?string => $record->parent_id === null ? '仅二级分类可开启' : null),
                ToggleColumn::make('show_in_catalog')
                    ->label('产品中心 Tab')
                    ->disabled(fn (Category $record): bool => $record->parent_id === null)
                    ->tooltip(fn (Category $record): ?string => $record->parent_id === null ? '仅二级分类可开启' : null),
                ToggleColumn::make('is_active')
                    ->label('启用'),
                ToggleColumn::make('is_home_show')
                    ->label('首页')
                    ->disabled(fn (Category $record): bool => ! $record->isRoot())
                    ->tooltip(fn (Category $record): ?string => ! $record->isRoot() ? '仅一级分类可开启' : null),
                ToggleColumn::make('is_home_featured')
                    ->label('首页主推')
                    ->disabled(fn (Category $record): bool => ! $record->isRoot())
                    ->tooltip(fn (Category $record): ?string => ! $record->isRoot() ? '仅一级分类可开启' : null),
            ])
            ->filters([
                SelectFilter::make('level')
                    ->label('层级')
                    ->options([
                        'root' => '一级分类',
                        'child' => '二级分类',
                    ])
                    ->query(function ($query, array $data) {
                        return match ($data['value'] ?? null) {
                            'root' => $query->whereNull('parent_id'),
                            'child' => $query->whereNotNull('parent_id'),
                            default => $query,
                        };
                    }),
            ])
            ->defaultPaginationPageOption(50)
            ->paginationPageOptions([25, 50, 100, 'all'])
            ->recordActions(ResourceTableActions::recordActions())
            ->toolbarActions(ResourceTableActions::toolbarActions());
    }
}

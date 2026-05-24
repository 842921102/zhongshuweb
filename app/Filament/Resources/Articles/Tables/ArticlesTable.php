<?php

namespace App\Filament\Resources\Articles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ArticlesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('封面')
                    ->disk('public'),
                TextColumn::make('title')
                    ->label('标题')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('category.name')
                    ->label('分类')
                    ->badge()
                    ->color('success')
                    ->sortable(),
                IconColumn::make('is_published')
                    ->label('已发布')
                    ->boolean(),
                IconColumn::make('is_featured')
                    ->label('主推')
                    ->boolean(),
                IconColumn::make('is_home_show')
                    ->label('首页')
                    ->boolean(),
                TextColumn::make('views')
                    ->label('浏览')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('published_at')
                    ->label('发布时间')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_published')->label('发布状态'),
                TernaryFilter::make('is_featured')->label('列表主推'),
                TernaryFilter::make('is_home_show')->label('首页展示'),
                SelectFilter::make('category_id')
                    ->label('新闻分类')
                    ->relationship('category', 'name'),
                SelectFilter::make('locale')
                    ->label('语言')
                    ->options(['zh-cn' => '中文', 'en-us' => 'English']),
            ])
            ->recordActions([
                EditAction::make()->label('编辑'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

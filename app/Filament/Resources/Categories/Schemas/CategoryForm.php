<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Filament\Support\OverlayCopyColorFields;
use App\Models\Category;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('parent_id')
                    ->label('父级分类')
                    ->relationship(
                        name: 'parent',
                        titleAttribute: 'name',
                        modifyQueryUsing: function (Builder $query, $livewire): Builder {
                            return $query
                                ->whereNull('parent_id')
                                ->when(
                                    property_exists($livewire, 'record') && $livewire->record,
                                    fn (Builder $q) => $q->where('id', '!=', $livewire->record->getKey()),
                                )
                                ->orderBy('sort_order');
                        },
                    )
                    ->searchable()
                    ->preload()
                    ->placeholder('无（一级分类）')
                    ->live()
                    ->disabled(fn (?Category $record): bool => (bool) $record?->children()->exists())
                    ->helperText(fn (?Category $record): string => $record?->children()->exists()
                        ? '该分类下已有子分类，不能改为二级分类'
                        : '留空为一级分类；二级分类需选择一级分类作为上级'),
                TextInput::make('name')
                    ->label('分类名称')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, Get $get, ?string $state): void {
                        if (filled($get('slug'))) {
                            return;
                        }
                        $slug = Str::slug($state ?? '');
                        if ($slug !== '') {
                            $set('slug', $slug);
                        }
                    }),
                TextInput::make('slug')
                    ->label('URL 别名')
                    ->maxLength(255)
                    ->helperText('留空将根据名称自动生成（中文名称一般为 category-{id}）')
                    ->unique(ignoreRecord: true),
                TextInput::make('subtitle')->label('副标题')->maxLength(255),
                Textarea::make('description')->label('描述')->columnSpanFull(),
                FileUpload::make('icon')->label('图标')->image()->directory('categories')->disk('public'),
                FileUpload::make('cover_image')->label('封面图（PC / 默认）')->image()->directory('categories')->disk('public'),
                FileUpload::make('cover_image_mobile')->label('封面图（手机端，可选）')->image()->directory('categories')->disk('public')
                    ->helperText('留空则小屏使用 PC 封面；建议竖版或更聚焦主体'),
                TextInput::make('link')->label('链接')->maxLength(255)->columnSpanFull(),
                OverlayCopyColorFields::section('用于首页「解决方案」大图与双卡片的标题、副标题'),
                TextInput::make('sort_order')->label('排序')->numeric()->default(0),
                Toggle::make('is_home_show')
                    ->label('首页展示')
                    ->disabled(fn (Get $get): bool => filled($get('parent_id')))
                    ->helperText(fn (Get $get): ?string => filled($get('parent_id')) ? '仅一级分类可开启（解决方案区）' : null),
                Toggle::make('is_home_featured')
                    ->label('首页主推(解决方案大图)')
                    ->disabled(fn (Get $get): bool => filled($get('parent_id')))
                    ->helperText(fn (Get $get): ?string => filled($get('parent_id')) ? '仅一级分类可开启' : null),
                Toggle::make('is_station_tab')
                    ->label('首页 Tab')
                    ->disabled(fn (Get $get): bool => blank($get('parent_id')))
                    ->helperText(fn (Get $get): ?string => blank($get('parent_id'))
                        ? '仅二级分类可开启'
                        : '显示在首页「全系产品站」分类 Tab 行'),
                Toggle::make('show_in_catalog')
                    ->label('产品中心 Tab')
                    ->disabled(fn (Get $get): bool => blank($get('parent_id')))
                    ->helperText(fn (Get $get): ?string => blank($get('parent_id'))
                        ? '仅二级分类可开启'
                        : '显示在产品中心页 /products Banner 下方分类栏'),
                Toggle::make('is_active')->label('启用')->default(true),
                Select::make('locale')->label('语言')->options(['zh-cn' => '中文', 'en-us' => 'English'])->default('zh-cn'),
            ]);
    }
}

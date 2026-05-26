<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
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
                    ->relationship('parent', 'name')
                    ->searchable()
                    ->preload()
                    ->placeholder('无（一级分类）'),
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
                TextInput::make('sort_order')->label('排序')->numeric()->default(0),
                Toggle::make('is_home_show')->label('首页展示'),
                Toggle::make('is_home_featured')->label('首页主推(解决方案大图)'),
                Toggle::make('is_station_tab')->label('产品站 Tab'),
                Toggle::make('is_active')->label('启用')->default(true),
                Select::make('locale')->label('语言')->options(['zh-cn' => '中文', 'en-us' => 'English'])->default('zh-cn'),
            ]);
    }
}

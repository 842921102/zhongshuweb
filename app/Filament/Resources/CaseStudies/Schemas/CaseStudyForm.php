<?php

namespace App\Filament\Resources\CaseStudies\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CaseStudyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('案例信息')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('标题')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                        TextInput::make('slug')
                            ->label('URL 别名')
                            ->maxLength(120)
                            ->helperText('用于 /cases/{slug}，留空将根据标题生成'),
                        Select::make('category_id')
                            ->label('场景分类')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->helperText('用于列表页筛选 Tab'),
                        TextInput::make('region')
                            ->label('地区')
                            ->maxLength(120)
                            ->placeholder('中国 / 广东省 / 深圳市'),
                        TextInput::make('scene_type')
                            ->label('场景类型（兼容）')
                            ->maxLength(80)
                            ->helperText('旧字段，建议优先使用「场景分类」'),
                        Textarea::make('excerpt')
                            ->label('摘要')
                            ->rows(3)
                            ->helperText('精选轮播与列表卡片展示')
                            ->columnSpanFull(),
                        Textarea::make('summary')
                            ->label('短描述')
                            ->rows(2)
                            ->columnSpanFull(),
                        RichEditor::make('content')
                            ->label('详情正文')
                            ->columnSpanFull(),
                        FileUpload::make('cover_image')
                            ->label('封面图')
                            ->image()
                            ->directory('cases')
                            ->disk('public')
                            ->visibility('public')
                            ->maxSize(5120)
                            ->imagePreviewHeight('160')
                            ->columnSpanFull(),
                        TagsInput::make('product_tags')
                            ->label('关联产品标签')
                            ->placeholder('输入后回车')
                            ->helperText('如：环卫清扫车、洗地机器人')
                            ->columnSpanFull(),
                        TextInput::make('detail_url')
                            ->label('外链详情（可选）')
                            ->maxLength(500)
                            ->helperText('无 slug 详情页时可填外链；有 slug 时优先站内详情'),
                    ]),
                Section::make('发布与展示')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Toggle::make('is_active')
                            ->label('启用')
                            ->default(true),
                        Toggle::make('is_featured')
                            ->label('精选轮播')
                            ->helperText('显示在案例页顶部大图轮播')
                            ->default(false),
                        Toggle::make('is_home_show')
                            ->label('首页展示')
                            ->default(false),
                        TextInput::make('sort_order')
                            ->label('排序')
                            ->numeric()
                            ->default(0),
                        Select::make('locale')
                            ->label('语言')
                            ->options(['zh-cn' => '中文', 'en-us' => 'English'])
                            ->default('zh-cn')
                            ->required(),
                        DateTimePicker::make('published_at')
                            ->label('发布时间')
                            ->helperText('留空表示立即发布'),
                        TextInput::make('meta_title')
                            ->label('SEO 标题')
                            ->maxLength(160),
                        Textarea::make('meta_description')
                            ->label('SEO 描述')
                            ->rows(2)
                            ->maxLength(500)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}

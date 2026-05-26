<?php

namespace App\Filament\Resources\IndustrySolutions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class IndustrySolutionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('解决方案编辑')
                ->columnSpanFull()
                ->tabs([
                    Tab::make('基本信息')
                        ->schema([
                            Section::make()
                                ->description('列表页卡片与详情页基础信息')
                                ->schema([
                                    TextInput::make('title')
                                        ->label('行业名称')
                                        ->required()
                                        ->maxLength(120)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                                    TextInput::make('slug')
                                        ->label('URL 别名')
                                        ->maxLength(120)
                                        ->helperText('前台地址：/industry-cases/{别名}，留空保存时自动生成'),
                                    Textarea::make('excerpt')
                                        ->label('卡片摘要')
                                        ->rows(3)
                                        ->helperText('列表页行业 Tab 下方卡片摘要')
                                        ->columnSpanFull(),
                                    Textarea::make('summary')
                                        ->label('详情 Hero 简介')
                                        ->rows(4)
                                        ->helperText('详情页顶部大图上的说明文字')
                                        ->columnSpanFull(),
                                    FileUpload::make('cover_image')
                                        ->label('封面图（PC / 默认）')
                                        ->image()
                                        ->directory('industry-cases')
                                        ->disk('public')
                                        ->visibility('public')
                                        ->maxSize(8192)
                                        ->imagePreviewHeight('160')
                                        ->helperText('列表页大图；详情 Hero 未单独配置轮播时使用'),
                                    FileUpload::make('cover_image_mobile')
                                        ->label('封面图（手机端，可选）')
                                        ->image()
                                        ->directory('industry-cases')
                                        ->disk('public')
                                        ->visibility('public')
                                        ->maxSize(8192)
                                        ->imagePreviewHeight('160'),
                                    TextInput::make('detail_button_text')
                                        ->label('列表卡片按钮文案')
                                        ->maxLength(40)
                                        ->placeholder('查看方案'),
                                    TextInput::make('external_url')
                                        ->label('外链（可选）')
                                        ->maxLength(500)
                                        ->helperText('无 slug 时可填外链；有 slug 时优先站内详情页')
                                        ->columnSpanFull(),
                                ])
                                ->columns(2),
                        ]),
                    Tab::make('详情 Hero')
                        ->schema([
                            Section::make()
                                ->description('对应详情页顶部 Banner；留空轮播时使用「基本信息」中的封面图')
                                ->schema([
                                    FileUpload::make('detail_data.hero.slides_gallery')
                                        ->label('Banner 图片（可多选）')
                                        ->image()
                                        ->multiple()
                                        ->reorderable()
                                        ->appendFiles()
                                        ->directory('industry-cases/detail')
                                        ->disk('public')
                                        ->visibility('public')
                                        ->maxFiles(12)
                                        ->maxSize(8192)
                                        ->imagePreviewHeight('120')
                                        ->helperText('可一次选择多张图片；拖拽可调整播放顺序')
                                        ->columnSpanFull(),
                                ]),
                        ]),
                    Tab::make('解决方案价值')
                        ->schema([
                            Section::make()
                                ->description('对应详情页锚点「解决方案价值」数据指标区块')
                                ->schema([
                                    TextInput::make('detail_data.stats.title')
                                        ->label('区块标题')
                                        ->maxLength(120)
                                        ->placeholder('赋能××行业效率升级')
                                        ->columnSpanFull(),
                                    Repeater::make('detail_data.stats.items')
                                        ->label('数据指标')
                                        ->schema([
                                            FileUpload::make('icon')
                                                ->label('图标')
                                                ->image()
                                                ->directory('industry-cases/icons')
                                                ->disk('public')
                                                ->visibility('public'),
                                            TextInput::make('label')
                                                ->label('指标名称')
                                                ->required()
                                                ->maxLength(80),
                                            TextInput::make('value')
                                                ->label('指标数值')
                                                ->required()
                                                ->maxLength(40)
                                                ->placeholder('2-3倍'),
                                        ])
                                        ->columns(3)
                                        ->defaultItems(0)
                                        ->addActionLabel('添加指标')
                                        ->reorderable()
                                        ->collapsible()
                                        ->itemLabel(fn (array $state): string => $state['label'] ?? '指标')
                                        ->columnSpanFull(),
                                    TextInput::make('detail_data.stats.footnote')
                                        ->label('脚注')
                                        ->maxLength(200)
                                        ->placeholder('*数据来源于众鼠云平台')
                                        ->columnSpanFull(),
                                ])
                                ->columns(2),
                        ]),
                    Tab::make('场景覆盖')
                        ->schema([
                            Section::make()
                                ->description('对应详情页锚点「场景覆盖与作业流程」')
                                ->schema([
                                    TextInput::make('detail_data.coverage.title')
                                        ->label('标题')
                                        ->maxLength(120)
                                        ->placeholder('全环节覆盖'),
                                    Textarea::make('detail_data.coverage.subtitle')
                                        ->label('副标题 / 流程说明')
                                        ->rows(3)
                                        ->placeholder('揽客 - 领位 - 点餐 - …')
                                        ->columnSpanFull(),
                                    FileUpload::make('detail_data.coverage.image_pc')
                                        ->label('示意图 PC')
                                        ->image()
                                        ->directory('industry-cases/detail')
                                        ->disk('public')
                                        ->visibility('public'),
                                    FileUpload::make('detail_data.coverage.image_mobile')
                                        ->label('示意图 手机')
                                        ->image()
                                        ->directory('industry-cases/detail')
                                        ->disk('public')
                                        ->visibility('public'),
                                ])
                                ->columns(2),
                        ]),
                    Tab::make('核心应用优势')
                        ->schema([
                            Section::make()
                                ->description('每个模块对应详情页一个场景区块（左轮播 + 右挑战/优势 + 下方产品方案）')
                                ->schema([
                                    Repeater::make('detail_data.scenes')
                                        ->label('应用场景模块')
                                        ->schema([
                                            TextInput::make('title')
                                                ->label('模块标题')
                                                ->required()
                                                ->maxLength(80)
                                                ->placeholder('营销配送'),
                                            FileUpload::make('slides_gallery')
                                                ->label('左侧轮播图（可多选）')
                                                ->image()
                                                ->multiple()
                                                ->reorderable()
                                                ->appendFiles()
                                                ->directory('industry-cases/detail')
                                                ->disk('public')
                                                ->visibility('public')
                                                ->maxFiles(15)
                                                ->maxSize(8192)
                                                ->imagePreviewHeight('100')
                                                ->helperText('可一次上传多张；拖拽调整顺序。前台自动轮播切换')
                                                ->columnSpanFull(),
                                            Textarea::make('slide_labels_lines')
                                                ->label('图下标题（可选）')
                                                ->rows(3)
                                                ->helperText('每张图一行，顺序与上方图片从左到右一致')
                                                ->columnSpanFull(),
                                            Textarea::make('challenge')
                                                ->label('场景挑战')
                                                ->rows(5)
                                                ->helperText('每行一条，可用 • 开头')
                                                ->columnSpanFull(),
                                            Textarea::make('advantage')
                                                ->label('方案优势')
                                                ->rows(5)
                                                ->helperText('每行一条，可用 • 开头')
                                                ->columnSpanFull(),
                                            Repeater::make('products')
                                                ->label('机器人解决方案')
                                                ->schema([
                                                    FileUpload::make('image')
                                                        ->label('产品图')
                                                        ->image()
                                                        ->directory('industry-cases/detail')
                                                        ->disk('public')
                                                        ->visibility('public'),
                                                    TextInput::make('title')
                                                        ->label('产品名称')
                                                        ->maxLength(80),
                                                    Textarea::make('bullets')
                                                        ->label('卖点')
                                                        ->rows(4)
                                                        ->helperText('每行一条'),
                                                    TextInput::make('url')
                                                        ->label('链接')
                                                        ->maxLength(500)
                                                        ->placeholder('/products/xxx'),
                                                    TextInput::make('link_text')
                                                        ->label('链接文案')
                                                        ->maxLength(40)
                                                        ->default('了解更多'),
                                                ])
                                                ->defaultItems(0)
                                                ->addActionLabel('添加产品')
                                                ->reorderable()
                                                ->collapsible()
                                                ->columns(2)
                                                ->columnSpanFull(),
                                        ])
                                        ->defaultItems(0)
                                        ->addActionLabel('添加场景模块')
                                        ->reorderable()
                                        ->collapsible()
                                        ->itemLabel(fn (array $state): string => $state['title'] ?? '场景模块')
                                        ->columnSpanFull(),
                                ]),
                        ]),
                    Tab::make('补充正文')
                        ->schema([
                            Section::make()
                                ->description('可选；显示在场景模块之后，用于额外图文说明')
                                ->schema([
                                    RichEditor::make('content')
                                        ->label('正文内容')
                                        ->columnSpanFull(),
                                ]),
                        ]),
                    Tab::make('发布设置')
                        ->schema([
                            Section::make()->schema([
                                Toggle::make('is_active')
                                    ->label('启用')
                                    ->default(true),
                                TextInput::make('sort_order')
                                    ->label('排序')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('数字越小越靠前；同时决定列表页顶部 Tab 顺序'),
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
                            ])->columns(2),
                        ]),
                ]),
        ]);
    }
}

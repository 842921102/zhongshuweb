<?php

namespace App\Filament\Resources\Articles\Schemas;

use App\Filament\Resources\ArticleCategories\Schemas\ArticleCategoryForm;
use App\Models\ArticleCategory;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('新闻')->columnSpanFull()->tabs([
                Tab::make('内容')->schema([
                    Section::make()->columns(2)->schema([
                        Select::make('category_id')
                            ->label('新闻分类')
                            ->relationship(
                                name: 'category',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn ($query, Get $get) => $query
                                    ->where('locale', $get('locale') ?? 'zh-cn')
                                    ->orderBy('sort_order'),
                            )
                            ->required()
                            ->searchable()
                            ->preload()
                            ->createOptionForm(ArticleCategoryForm::quickFields())
                            ->createOptionUsing(function (array $data): int {
                                $data['locale'] ??= 'zh-cn';
                                $data['is_active'] ??= true;

                                return ArticleCategory::query()->create($data)->getKey();
                            }),
                        TextInput::make('title')
                            ->label('标题')
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
                            ->helperText('留空保存时自动生成')
                            ->unique(ignoreRecord: true),
                        TextInput::make('author')
                            ->label('作者')
                            ->maxLength(255),
                        Textarea::make('summary')
                            ->label('摘要')
                            ->rows(3)
                            ->columnSpanFull()
                            ->helperText('列表页与 SEO 摘要'),
                        RichEditor::make('content')
                            ->label('正文')
                            ->columnSpanFull(),
                        FileUpload::make('cover_image')
                            ->label('封面图（PC / 默认）')
                            ->image()
                            ->directory('articles')
                            ->disk('public'),
                        FileUpload::make('cover_image_mobile')
                            ->label('封面图（手机端，可选）')
                            ->image()
                            ->directory('articles')
                            ->disk('public')
                            ->helperText('留空则小屏使用 PC 封面')
                            ->columnSpanFull(),
                    ]),
                ]),
                Tab::make('发布')->schema([
                    Section::make()->columns(2)->schema([
                        Toggle::make('is_published')->label('已发布')->default(false),
                        Toggle::make('is_featured')->label('列表主推')->helperText('新闻列表页顶部大卡'),
                        Toggle::make('is_home_show')->label('首页展示'),
                        TextInput::make('sort_order')->label('排序')->numeric()->default(0),
                        Select::make('locale')
                            ->label('语言')
                            ->options(['zh-cn' => '中文', 'en-us' => 'English'])
                            ->default('zh-cn')
                            ->live(),
                        DateTimePicker::make('published_at')->label('发布时间')->default(now()),
                    ]),
                ]),
                Tab::make('SEO')->schema([
                    Section::make()->schema([
                        TextInput::make('seo_title')->label('SEO 标题')->maxLength(255),
                        Textarea::make('seo_description')->label('SEO 描述')->rows(4),
                    ]),
                ]),
            ]),
        ]);
    }
}

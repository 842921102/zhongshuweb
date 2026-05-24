<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('页面内容')
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
                            ->maxLength(255)
                            ->helperText('例如 about、services，留空将自动生成'),
                        TextInput::make('subtitle')
                            ->label('副标题')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        RichEditor::make('content')
                            ->label('正文')
                            ->columnSpanFull(),
                        FileUpload::make('cover_image')
                            ->label('封面图')
                            ->image()
                            ->directory('pages')
                            ->disk('public')
                            ->columnSpanFull(),
                        TextInput::make('sort_order')
                            ->label('排序')
                            ->numeric()
                            ->default(0),
                    ]),
                Section::make('发布')
                    ->schema([
                        Toggle::make('is_published')
                            ->label('已发布')
                            ->default(false),
                        DateTimePicker::make('published_at')
                            ->label('发布时间')
                            ->default(now()),
                    ]),
                Section::make('SEO')
                    ->schema([
                        TextInput::make('seo_title')
                            ->label('SEO 标题')
                            ->maxLength(255),
                        Textarea::make('seo_description')
                            ->label('SEO 描述')
                            ->rows(4),
                    ]),
            ]);
    }
}

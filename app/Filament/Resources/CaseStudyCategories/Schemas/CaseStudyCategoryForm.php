<?php

namespace App\Filament\Resources\CaseStudyCategories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CaseStudyCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('分类名称')
                            ->required()
                            ->maxLength(80)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                        TextInput::make('slug')
                            ->label('URL 标识')
                            ->required()
                            ->maxLength(80)
                            ->helperText('列表页筛选参数 ?category=slug'),
                        TextInput::make('sort_order')
                            ->label('排序')
                            ->numeric()
                            ->default(0),
                        Select::make('locale')
                            ->label('语言')
                            ->options(['zh-cn' => '中文', 'en-us' => 'English'])
                            ->default('zh-cn')
                            ->required(),
                        Toggle::make('is_active')
                            ->label('启用')
                            ->default(true),
                    ]),
            ]);
    }
}

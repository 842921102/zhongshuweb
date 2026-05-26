<?php

namespace App\Filament\Resources\SiteNavMenus\Schemas;

use App\Models\SiteNavMenu;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class SiteNavMenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('菜单项')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Select::make('menu_type')
                            ->label('菜单类型')
                            ->options(SiteNavMenu::TYPE_LABELS)
                            ->default(SiteNavMenu::TYPE_LINK)
                            ->required()
                            ->live(),
                        Select::make('menu_key')
                            ->label('系统标识')
                            ->options(fn (?SiteNavMenu $record): array => static::menuKeyOptions($record))
                            ->searchable()
                            ->nullable()
                            ->disabled(fn (?SiteNavMenu $record): bool => $record?->isSystem() ?? false)
                            ->dehydrated()
                            ->helperText(fn (?SiteNavMenu $record): ?string => $record?->isSystem()
                                ? '系统菜单标识不可修改'
                                : '可选；用于同步默认菜单时识别'),
                        TextInput::make('label')
                            ->label('显示名称')
                            ->required()
                            ->maxLength(120),
                        TextInput::make('url')
                            ->label('链接地址')
                            ->required()
                            ->maxLength(500)
                            ->default('#')
                            ->placeholder('/ 或 #home-case 或 https://')
                            ->helperText(fn (Get $get): string => $get('menu_type') === SiteNavMenu::TYPE_PRODUCT_MEGA
                                ? '产品菜单主链接，默认 #home-products；下拉内容来自「产品分类」'
                                : '支持站内锚点 #home-xxx、相对路径 /about、站外 https://'),
                        TextInput::make('route_keys')
                            ->label('路由标识 (data-route)')
                            ->maxLength(100)
                            ->placeholder('case,cases')
                            ->helperText('用于导航高亮，多个用英文逗号分隔'),
                        TextInput::make('search_keywords')
                            ->label('搜索关键词 (data-search)')
                            ->maxLength(255)
                            ->placeholder('案例 case,cases')
                            ->helperText('顶部站内搜索匹配用，建议中英文都写')
                            ->columnSpanFull(),
                        Select::make('parent_id')
                            ->label('父级菜单')
                            ->relationship(
                                'parent',
                                'label',
                                fn ($query, ?SiteNavMenu $record) => $query
                                    ->whereNull('parent_id')
                                    ->when($record, fn ($q) => $q->where('id', '!=', $record->id))
                            )
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->helperText('预留二级菜单；当前前台仅渲染顶级项'),
                        TextInput::make('sort_order')
                            ->label('排序')
                            ->numeric()
                            ->default(0),
                        Select::make('locale')
                            ->label('语言')
                            ->options(['zh-cn' => '中文', 'en-us' => 'English'])
                            ->default('zh-cn')
                            ->required()
                            ->helperText('同一「系统标识」在同一语言下只能有一条'),
                        Toggle::make('open_in_new_tab')
                            ->label('新窗口打开')
                            ->default(false)
                            ->inline(false),
                        Toggle::make('is_active')
                            ->label('启用')
                            ->default(true)
                            ->inline(false),
                    ]),
            ]);
    }

    /** @return array<string, string> */
    protected static function menuKeyOptions(?SiteNavMenu $record = null): array
    {
        $options = SiteNavMenu::SYSTEM_KEY_LABELS;

        if ($record?->menu_key && ! isset($options[$record->menu_key])) {
            $options[$record->menu_key] = $record->menu_key.' ('.$record->menu_key.')';
        }

        return $options;
    }
}

<?php

namespace App\Filament\Resources\SitePartners\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SitePartnerForm
{
    private const LOGO_SIZE = '推荐尺寸：约 200×80 px 或等比例 PNG/SVG（透明底更佳），单张 ≤2MB';

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('基本信息')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('合作伙伴名称')
                            ->required()
                            ->maxLength(120),
                        TextInput::make('link')
                            ->label('链接地址')
                            ->url()
                            ->maxLength(500)
                            ->placeholder('https://')
                            ->helperText('留空则前台点击无跳转'),
                        FileUpload::make('logo')
                            ->label('Logo')
                            ->image()
                            ->directory('partners')
                            ->disk('public')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->imagePreviewHeight('80')
                            ->openable()
                            ->downloadable()
                            ->helperText(self::LOGO_SIZE.'；已使用 /home-assets/ 静态路径时，重新上传可替换为存储文件')
                            ->columnSpanFull(),
                        TextInput::make('sort_order')
                            ->label('排序')
                            ->numeric()
                            ->default(0)
                            ->helperText('数字越小越靠前'),
                        Select::make('locale')
                            ->label('语言')
                            ->options(['zh-cn' => '中文', 'en-us' => 'English'])
                            ->default('zh-cn')
                            ->required(),
                        Toggle::make('is_home_show')
                            ->label('首页展示')
                            ->default(true)
                            ->inline(false),
                        Toggle::make('is_active')
                            ->label('启用')
                            ->default(true)
                            ->inline(false),
                    ]),
            ]);
    }
}

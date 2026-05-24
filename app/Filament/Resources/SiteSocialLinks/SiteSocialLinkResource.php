<?php

namespace App\Filament\Resources\SiteSocialLinks;

use App\Filament\Resources\SiteSocialLinks\Pages\ManageSiteSocialLinks;
use App\Models\SiteSocialLink;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class SiteSocialLinkResource extends Resource
{
    protected static ?string $model = SiteSocialLink::class;

    protected static string|UnitEnum|null $navigationGroup = '系统设置';

    protected static ?string $navigationLabel = '页脚社交';

    protected static ?string $modelLabel = '社交链接';

    protected static ?string $pluralModelLabel = '社交链接';

    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShare;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('名称')
                ->required()
                ->maxLength(64)
                ->placeholder('抖音 / 微信'),
            TextInput::make('icon')
                ->label('图标路径')
                ->required()
                ->maxLength(500)
                ->placeholder('/home-assets/social-wechat.svg')
                ->helperText('SVG 图标，用于页脚按钮展示'),
            Select::make('type')
                ->label('类型')
                ->options([
                    SiteSocialLink::TYPE_QR => '扫码（弹出二维码）',
                    SiteSocialLink::TYPE_LINK => '外链',
                ])
                ->default(SiteSocialLink::TYPE_QR)
                ->required()
                ->live(),
            TextInput::make('url')
                ->label('外链地址')
                ->maxLength(500)
                ->visible(fn ($get) => $get('type') === SiteSocialLink::TYPE_LINK),
            FileUpload::make('qr_image')
                ->label('二维码图片')
                ->image()
                ->directory('social-qr')
                ->disk('public')
                ->visible(fn ($get) => $get('type') === SiteSocialLink::TYPE_QR),
            TextInput::make('sort_order')
                ->label('排序')
                ->numeric()
                ->default(0),
            Toggle::make('is_active')
                ->label('启用')
                ->default(true),
            TextInput::make('locale')
                ->label('语言')
                ->default('zh-cn')
                ->maxLength(10),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('sort_order')->label('排序')->width(70),
                TextColumn::make('name')->label('名称'),
                TextColumn::make('type')->label('类型'),
                TextColumn::make('url')->label('链接')->limit(30),
                IconColumn::make('is_active')->label('启用')->boolean(),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSiteSocialLinks::route('/'),
        ];
    }
}

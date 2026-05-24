<?php

namespace App\Filament\Resources\CompanyHonors;

use App\Filament\Resources\CompanyHonors\Pages\ManageCompanyHonors;
use App\Models\CompanyHonor;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\ImageColumn;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class CompanyHonorResource extends Resource
{
    protected static ?string $model = CompanyHonor::class;

    protected static string|UnitEnum|null $navigationGroup = '关于我们';

    protected static ?string $navigationLabel = '品牌荣誉（图片）';

    protected static ?string $modelLabel = '荣誉/资质';

    protected static ?int $navigationSort = 4;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTrophy;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')
                ->label('名称')
                ->required()
                ->maxLength(200)
                ->placeholder('高新技术企业证书'),
            Select::make('category')
                ->label('类型')
                ->options([
                    'qualification' => '企业资质',
                    'award' => '奖牌荣誉',
                    'certificate' => '体系认证',
                    'patent' => '专利/软著',
                    'other' => '其他',
                ])
                ->default('qualification')
                ->required(),
            FileUpload::make('image')
                ->label('证书/奖牌图片')
                ->image()
                ->directory('company-about/honors')
                ->disk('public')
                ->visibility('public')
                ->required()
                ->maxSize(10240)
                ->imagePreviewHeight('200')
                ->openable()
                ->downloadable()
                ->helperText('推荐上传竖版证书、奖牌、牌匾照片，PNG/JPG，背景尽量清晰')
                ->columnSpanFull(),
            TextInput::make('sort_order')->label('排序')->numeric()->default(0),
            Toggle::make('is_active')->label('启用')->default(true),
            TextInput::make('locale')->label('语言')->default('zh-cn'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->label('图片')->square()->height(48),
                TextColumn::make('title')->label('名称'),
                TextColumn::make('category')
                    ->label('类型')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'qualification' => '企业资质',
                        'award' => '奖牌荣誉',
                        'certificate' => '体系认证',
                        'patent' => '专利/软著',
                        default => '其他',
                    }),
                TextColumn::make('sort_order')->label('排序'),
            ])
            ->defaultSort('sort_order')
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageCompanyHonors::route('/')];
    }
}

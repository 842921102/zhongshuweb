<?php

namespace App\Filament\Resources\CompanyCultureValues;

use App\Filament\Resources\CompanyCultureValues\Pages\ManageCompanyCultureValues;
use App\Models\CompanyCultureValue;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class CompanyCultureValueResource extends Resource
{
    protected static ?string $model = CompanyCultureValue::class;

    protected static string|UnitEnum|null $navigationGroup = '关于我们';

    protected static ?string $navigationLabel = '企业文化条目';

    protected static ?string $modelLabel = '条目';

    protected static ?string $pluralModelLabel = '企业文化条目';

    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHeart;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('label')->label('要义（如：重本）')->required()->maxLength(20),
            TextInput::make('subtitle')->label('副标题')->maxLength(120)->placeholder('深耕根基，筑牢内核'),
            Textarea::make('essence')->label('核心内涵')->rows(4)->columnSpanFull(),
            Textarea::make('practice')->label('科技实践（选填）')->rows(3)->columnSpanFull(),
            FileUpload::make('icon')
                ->label('图标')
                ->image()
                ->directory('company-about/culture')
                ->disk('public')
                ->visibility('public')
                ->maxSize(2048)
                ->imagePreviewHeight('80')
                ->helperText('推荐 SVG/PNG 正方形图标'),
            TextInput::make('sort_order')->label('排序')->numeric()->default(0),
            Toggle::make('is_active')->label('启用')->default(true),
            TextInput::make('locale')->label('语言')->default('zh-cn'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label')->label('要义'),
                TextColumn::make('subtitle')->label('副标题')->limit(24),
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
        return ['index' => ManageCompanyCultureValues::route('/')];
    }
}

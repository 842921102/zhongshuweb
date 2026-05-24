<?php

namespace App\Filament\Resources\JoinWhyCards;

use App\Filament\Resources\JoinWhyCards\Pages\ManageJoinWhyCards;
use App\Models\JoinWhyCard;
use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class JoinWhyCardResource extends Resource
{
    protected static ?string $model = JoinWhyCard::class;

    protected static string|UnitEnum|null $navigationGroup = '加入我们';

    protected static ?string $navigationLabel = '为什么加入';

    protected static ?int $navigationSort = 4;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('icon_char')->label('图标字')->maxLength(10)->placeholder('业'),
            TextInput::make('title')->label('标题')->required()->maxLength(120),
            Textarea::make('description')->label('描述')->rows(3)->columnSpanFull(),
            TextInput::make('sort_order')->label('排序')->numeric()->default(0),
            Toggle::make('is_active')->label('启用')->default(true),
            TextInput::make('locale')->label('语言')->default('zh-cn'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->defaultSort('sort_order')->reorderable('sort_order')->columns([
            TextColumn::make('sort_order')->width(70),
            TextColumn::make('icon_char')->label('图标'),
            TextColumn::make('title')->label('标题'),
        ])->recordActions([
            \Filament\Actions\EditAction::make(),
            \Filament\Actions\DeleteAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageJoinWhyCards::route('/')];
    }
}

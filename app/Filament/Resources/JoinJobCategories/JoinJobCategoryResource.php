<?php

namespace App\Filament\Resources\JoinJobCategories;

use App\Filament\Resources\JoinJobCategories\Pages\ManageJoinJobCategories;
use App\Models\JoinJobCategory;
use App\Support\Filament\ResourceTableActions;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use UnitEnum;

class JoinJobCategoryResource extends Resource
{
    protected static ?string $model = JoinJobCategory::class;

    protected static string|UnitEnum|null $navigationGroup = '加入我们';

    protected static ?string $navigationLabel = '岗位分类';

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('名称')->required()->maxLength(80),
            TextInput::make('slug')->label('标识')->maxLength(80)->helperText('前台 ?category=slug'),
            TextInput::make('sort_order')->label('排序')->numeric()->default(0),
            Toggle::make('is_active')->label('启用')->default(true),
            Select::make('locale')->label('语言')->options(['zh-cn' => '中文'])->default('zh-cn'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->defaultSort('sort_order')->reorderable('sort_order')->columns([
            TextColumn::make('sort_order')->label('排序')->width(70),
            TextColumn::make('name')->label('名称'),
            TextColumn::make('slug')->badge(),
            ToggleColumn::make('is_active')->label('启用'),
        ])
            ->recordActions(ResourceTableActions::recordActions())
            ->toolbarActions(ResourceTableActions::toolbarActions());
    }

    public static function getPages(): array
    {
        return ['index' => ManageJoinJobCategories::route('/')];
    }
}

<?php

namespace App\Filament\Resources\JoinPositions;

use App\Filament\Resources\JoinPositions\Pages\CreateJoinPosition;
use App\Filament\Resources\JoinPositions\Pages\EditJoinPosition;
use App\Filament\Resources\JoinPositions\Pages\ListJoinPositions;
use App\Models\JoinPosition;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class JoinPositionResource extends Resource
{
    protected static ?string $model = JoinPosition::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static string|UnitEnum|null $navigationGroup = '加入我们';

    protected static ?string $navigationLabel = '开放岗位';

    protected static ?string $modelLabel = '岗位';

    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            Select::make('category_id')->label('岗位分类')->relationship('category', 'name')->searchable()->preload(),
            TextInput::make('title')->label('岗位名称')->required()->maxLength(200)->columnSpanFull(),
            TextInput::make('department_label')->label('部门标签')->maxLength(80),
            TextInput::make('location')->label('工作地点')->maxLength(120),
            TextInput::make('employment_type')->label('用工类型')->placeholder('全职')->maxLength(40),
            TextInput::make('experience')->label('经验要求')->placeholder('经验 3 年以上')->maxLength(80),
            Textarea::make('summary')->label('岗位描述')->rows(4)->columnSpanFull(),
            TagsInput::make('tags')->label('技能标签')->columnSpanFull(),
            TextInput::make('sort_order')->label('排序')->numeric()->default(0),
            Toggle::make('is_active')->label('启用')->default(true),
            Select::make('locale')->label('语言')->options(['zh-cn' => '中文'])->default('zh-cn'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('sort_order')->label('排序')->width(70),
                TextColumn::make('title')->label('岗位')->searchable(),
                TextColumn::make('category.name')->label('分类')->badge(),
                TextColumn::make('location')->label('地点'),
                IconColumn::make('is_active')->label('启用')->boolean(),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                \Filament\Actions\CreateAction::make()->label('新增岗位'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJoinPositions::route('/'),
            'create' => CreateJoinPosition::route('/create'),
            'edit' => EditJoinPosition::route('/{record}/edit'),
        ];
    }
}

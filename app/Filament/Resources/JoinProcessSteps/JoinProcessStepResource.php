<?php

namespace App\Filament\Resources\JoinProcessSteps;

use App\Filament\Resources\JoinProcessSteps\Pages\ManageJoinProcessSteps;
use App\Models\JoinProcessStep;
use App\Support\Filament\ResourceTableActions;
use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use UnitEnum;

class JoinProcessStepResource extends Resource
{
    protected static ?string $model = JoinProcessStep::class;

    protected static string|UnitEnum|null $navigationGroup = '加入我们';

    protected static ?string $navigationLabel = '招聘流程';

    protected static ?int $navigationSort = 6;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQueueList;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')->label('步骤标题')->required()->maxLength(120),
            Textarea::make('description')->label('说明')->rows(3)->columnSpanFull(),
            TextInput::make('sort_order')->label('排序')->numeric()->default(0),
            Toggle::make('is_active')->label('启用')->default(true),
            TextInput::make('locale')->label('语言')->default('zh-cn'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->defaultSort('sort_order')->reorderable('sort_order')->columns([
            TextColumn::make('sort_order')->width(70),
            TextColumn::make('title')->label('步骤'),
            ToggleColumn::make('is_active')->label('启用'),
        ])
            ->recordActions(ResourceTableActions::recordActions())
            ->toolbarActions(ResourceTableActions::toolbarActions());
    }

    public static function getPages(): array
    {
        return ['index' => ManageJoinProcessSteps::route('/')];
    }
}

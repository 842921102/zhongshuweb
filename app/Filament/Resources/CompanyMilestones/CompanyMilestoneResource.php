<?php

namespace App\Filament\Resources\CompanyMilestones;

use App\Filament\Resources\CompanyMilestones\Pages\ManageCompanyMilestones;
use App\Models\CompanyMilestone;
use App\Support\Filament\ResourceTableActions;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use UnitEnum;

class CompanyMilestoneResource extends Resource
{
    protected static ?string $model = CompanyMilestone::class;

    protected static string|UnitEnum|null $navigationGroup = '关于我们';

    protected static ?string $navigationLabel = '发展历程';

    protected static ?string $modelLabel = '历程';

    protected static ?string $pluralModelLabel = '发展历程';

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('year')->label('年份')->numeric()->required(),
            TextInput::make('month_label')->label('月份')->maxLength(20)->placeholder('1月'),
            TextInput::make('title')->label('事件')->required()->maxLength(500)->columnSpanFull(),
            FileUpload::make('image')
                ->label('配图')
                ->image()
                ->directory('company-about/milestones')
                ->disk('public')
                ->visibility('public')
                ->maxSize(5120)
                ->imagePreviewHeight('160')
                ->openable()
                ->columnSpanFull(),
            TextInput::make('sort_order')->label('排序')->numeric()->default(0),
            Toggle::make('is_active')->label('启用')->default(true),
            TextInput::make('locale')->label('语言')->default('zh-cn')->maxLength(10),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('year')->label('年')->sortable(),
                TextColumn::make('month_label')->label('月'),
                TextColumn::make('title')->label('事件')->limit(40),
                ToggleColumn::make('is_active')->label('启用'),
                TextColumn::make('sort_order')->label('排序'),
            ])
            ->defaultSort('year', 'desc')
            ->recordActions(ResourceTableActions::recordActions())
            ->toolbarActions(ResourceTableActions::toolbarActions());
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCompanyMilestones::route('/'),
        ];
    }
}

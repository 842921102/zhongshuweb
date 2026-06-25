<?php

namespace App\Filament\Resources\CompanyTeamMembers;

use App\Filament\Resources\CompanyTeamMembers\Pages\ManageCompanyTeamMembers;
use App\Models\CompanyTeamMember;
use App\Support\Filament\ResourceTableActions;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
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

class CompanyTeamMemberResource extends Resource
{
    protected static ?string $model = CompanyTeamMember::class;

    protected static string|UnitEnum|null $navigationGroup = '关于我们';

    protected static ?string $navigationLabel = '团队成员';

    protected static ?string $modelLabel = '成员';

    protected static ?int $navigationSort = 5;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('姓名')->required()->maxLength(80),
            TextInput::make('role')->label('职位')->maxLength(120)->placeholder('首席技术官'),
            Textarea::make('bio')->label('简介')->rows(4)->columnSpanFull(),
            FileUpload::make('photo')
                ->label('照片')
                ->image()
                ->directory('company-about/team')
                ->disk('public')
                ->visibility('public')
                ->maxSize(5120)
                ->imagePreviewHeight('160')
                ->openable()
                ->columnSpanFull(),
            Toggle::make('is_featured')
                ->label('置顶展示（团队首位）')
                ->helperText('开启后显示在「团队介绍」最上方，仅建议设置 1 人')
                ->default(false),
            TextInput::make('sort_order')->label('排序')->numeric()->default(0),
            Toggle::make('is_active')->label('启用')->default(true),
            TextInput::make('locale')->label('语言')->default('zh-cn'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('姓名'),
                TextColumn::make('role')->label('职位'),
                ToggleColumn::make('is_featured')->label('置顶'),
                ToggleColumn::make('is_active')->label('启用'),
                TextColumn::make('sort_order')->label('排序'),
            ])
            ->defaultSort('sort_order')
            ->recordActions(ResourceTableActions::recordActions())
            ->toolbarActions(ResourceTableActions::toolbarActions());
    }

    public static function getPages(): array
    {
        return ['index' => ManageCompanyTeamMembers::route('/')];
    }
}

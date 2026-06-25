<?php

namespace App\Filament\Resources\SupportServiceRequests;

use App\Filament\Resources\SupportServiceRequests\Pages\EditSupportServiceRequest;
use App\Filament\Resources\SupportServiceRequests\Pages\ManageSupportServiceRequests;
use App\Models\SupportServiceRequest;
use App\Support\Filament\ResourceTableActions;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class SupportServiceRequestResource extends Resource
{
    protected static ?string $model = SupportServiceRequest::class;

    protected static string|UnitEnum|null $navigationGroup = '技术支持';

    protected static ?string $navigationLabel = '售后申请';

    protected static ?string $modelLabel = '售后申请';

    protected static ?int $navigationSort = 4;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInbox;

    public static function getNavigationBadge(): ?string
    {
        $count = SupportServiceRequest::query()
            ->where('status', SupportServiceRequest::STATUS_PENDING)
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            \Filament\Forms\Components\TextInput::make('name')->label('姓名')->disabled(),
            \Filament\Forms\Components\TextInput::make('phone')->label('电话')->disabled(),
            \Filament\Forms\Components\TextInput::make('email')->label('邮箱')->disabled(),
            \Filament\Forms\Components\TextInput::make('region')->label('地区')->disabled(),
            \Filament\Forms\Components\TextInput::make('topic')->label('主题')->disabled(),
            \Filament\Forms\Components\Select::make('status')
                ->label('处理状态')
                ->options(SupportServiceRequest::statusLabels())
                ->required(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->label('提交时间')->dateTime('Y-m-d H:i')->sortable(),
                TextColumn::make('name')->label('姓名')->searchable(),
                TextColumn::make('phone')->label('电话')->searchable(),
                TextColumn::make('topic')->label('主题'),
                TextColumn::make('region')->label('地区')->limit(30)->toggleable(),
                TextColumn::make('status')
                    ->label('状态')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => SupportServiceRequest::statusLabel($state))
                    ->color(fn (string $state): string => match ($state) {
                        SupportServiceRequest::STATUS_CONTACTED => 'info',
                        SupportServiceRequest::STATUS_CLOSED => 'gray',
                        default => 'warning',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->label('状态')
                    ->options(SupportServiceRequest::statusLabels()),
            ])
            ->recordActions(ResourceTableActions::recordActions(replicate: false, editLabel: '处理'))
            ->toolbarActions(ResourceTableActions::toolbarActions());
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSupportServiceRequests::route('/'),
            'edit' => EditSupportServiceRequest::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}

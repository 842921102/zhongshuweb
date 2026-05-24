<?php

namespace App\Filament\Resources\JoinApplications;

use App\Filament\Resources\JoinApplications\Pages\EditJoinApplication;
use App\Filament\Resources\JoinApplications\Pages\ListJoinApplications;
use App\Models\JoinApplication;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class JoinApplicationResource extends Resource
{
    protected static ?string $model = JoinApplication::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|UnitEnum|null $navigationGroup = '加入我们';

    protected static ?string $navigationLabel = '简历投递';

    protected static ?string $modelLabel = '简历投递';

    protected static ?string $pluralModelLabel = '简历投递';

    protected static ?int $navigationSort = 8;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInbox;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $count = JoinApplication::query()->where('status', JoinApplication::STATUS_PENDING)->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('position_title')->label('意向岗位')->disabled(),
            TextInput::make('name')->label('姓名')->disabled(),
            TextInput::make('phone')->label('电话')->disabled(),
            TextInput::make('email')->label('邮箱')->disabled(),
            TextInput::make('city')->label('城市')->disabled(),
            Textarea::make('message')->label('留言')->rows(3)->disabled()->columnSpanFull(),
            TextInput::make('resume_original_name')
                ->label('简历文件名')
                ->disabled()
                ->columnSpanFull()
                ->helperText(fn (?JoinApplication $record): ?string => $record?->resumeUrl()
                    ? '下载：'.$record->resumeUrl()
                    : null),
            Select::make('status')
                ->label('处理状态')
                ->options(JoinApplication::statusLabels())
                ->required(),
            Textarea::make('admin_note')->label('内部备注')->rows(4)->columnSpanFull(),
            TextInput::make('ip')->label('提交 IP')->disabled(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')->label('提交时间')->dateTime('Y-m-d H:i')->sortable(),
                TextColumn::make('position_title')->label('意向岗位')->searchable()->limit(24),
                TextColumn::make('name')->label('姓名')->searchable(),
                TextColumn::make('phone')->label('电话')->searchable(),
                TextColumn::make('email')->label('邮箱')->toggleable(),
                TextColumn::make('city')->label('城市')->toggleable(),
                TextColumn::make('resume_original_name')->label('简历')->limit(20),
                TextColumn::make('status')
                    ->label('状态')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => JoinApplication::statusLabels()[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        JoinApplication::STATUS_PENDING => 'warning',
                        JoinApplication::STATUS_CONTACTED, JoinApplication::STATUS_INTERVIEW => 'info',
                        JoinApplication::STATUS_HIRED => 'success',
                        JoinApplication::STATUS_REJECTED, JoinApplication::STATUS_CLOSED => 'gray',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('状态')
                    ->options(JoinApplication::statusLabels()),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make()->label('处理'),
            ])
            ->toolbarActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJoinApplications::route('/'),
            'edit' => EditJoinApplication::route('/{record}/edit'),
        ];
    }
}

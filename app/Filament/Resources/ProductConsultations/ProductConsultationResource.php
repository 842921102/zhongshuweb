<?php

namespace App\Filament\Resources\ProductConsultations;

use App\Filament\Resources\ProductConsultations\Pages\EditProductConsultation;
use App\Filament\Resources\ProductConsultations\Pages\ListProductConsultations;
use App\Models\ProductConsultation;
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

class ProductConsultationResource extends Resource
{
    protected static ?string $model = ProductConsultation::class;

    protected static string|UnitEnum|null $navigationGroup = '产品管理';

    protected static ?string $navigationLabel = '产品咨询';

    protected static ?string $modelLabel = '咨询记录';

    protected static ?string $pluralModelLabel = '产品咨询';

    protected static ?int $navigationSort = 4;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $count = ProductConsultation::query()->where('status', 'pending')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('product_name')->label('咨询产品')->disabled(),
            TextInput::make('name')->label('姓名')->disabled(),
            TextInput::make('phone')->label('电话')->disabled(),
            TextInput::make('email')->label('邮箱')->disabled(),
            TextInput::make('city')->label('城市')->disabled(),
            TextInput::make('topic')->label('主题')->disabled(),
            Textarea::make('remark')->label('留言')->rows(4)->disabled()->columnSpanFull(),
            Select::make('status')->label('处理状态')->options([
                'pending' => '待处理',
                'contacted' => '已联系',
                'closed' => '已关闭',
            ])->required(),
            TextInput::make('ip')->label('IP')->disabled(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')->label('提交时间')->dateTime('Y-m-d H:i')->sortable(),
                TextColumn::make('product_name')->label('产品')->searchable(),
                TextColumn::make('name')->label('姓名')->searchable(),
                TextColumn::make('phone')->label('电话')->searchable(),
                TextColumn::make('topic')->label('主题')->placeholder('—'),
                TextColumn::make('city')->label('城市')->toggleable(),
                TextColumn::make('status')
                    ->label('状态')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'contacted' => '已联系',
                        'closed' => '已关闭',
                        default => '待处理',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'contacted' => 'info',
                        'closed' => 'gray',
                        default => 'warning',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')->label('状态')->options([
                    'pending' => '待处理',
                    'contacted' => '已联系',
                    'closed' => '已关闭',
                ]),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make()->label('处理'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductConsultations::route('/'),
            'edit' => EditProductConsultation::route('/{record}/edit'),
        ];
    }
}

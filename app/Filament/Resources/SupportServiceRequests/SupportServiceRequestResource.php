<?php

namespace App\Filament\Resources\SupportServiceRequests;

use App\Filament\Resources\SupportServiceRequests\Pages\ManageSupportServiceRequests;
use App\Models\SupportServiceRequest;
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

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('姓名'),
                TextColumn::make('phone')->label('电话'),
                TextColumn::make('topic')->label('主题'),
                TextColumn::make('region')->label('地区')->limit(30),
                TextColumn::make('created_at')->label('提交时间')->dateTime('Y-m-d H:i'),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                \Filament\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageSupportServiceRequests::route('/')];
    }
}

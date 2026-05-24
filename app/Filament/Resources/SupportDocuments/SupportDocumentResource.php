<?php

namespace App\Filament\Resources\SupportDocuments;

use App\Filament\Resources\SupportDocuments\Pages\ManageSupportDocuments;
use App\Models\SupportDocument;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class SupportDocumentResource extends Resource
{
    protected static ?string $model = SupportDocument::class;

    protected static string|UnitEnum|null $navigationGroup = '技术支持';

    protected static ?string $navigationLabel = 'PDF 文档';

    protected static ?string $modelLabel = '技术文档';

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentArrowDown;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')->label('文档名称')->required()->maxLength(200),
            TextInput::make('category')->label('分类')->required()->placeholder('产品手册'),
            TextInput::make('version')->label('版本')->placeholder('v1.0'),
            TextInput::make('published_label')->label('发布月份')->placeholder('2026-04'),
            TextInput::make('page_count')->label('页数')->numeric(),
            FileUpload::make('file_path')->label('PDF 文件')->acceptedFileTypes(['application/pdf'])->directory('support/docs')->disk('public')->required()->columnSpanFull(),
            TextInput::make('file_size_label')->label('文件大小展示')->placeholder('26KB'),
            TextInput::make('sort_order')->label('排序')->numeric()->default(0),
            Toggle::make('is_active')->label('启用')->default(true),
            TextInput::make('locale')->label('语言')->default('zh-cn'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('名称')->searchable(),
                TextColumn::make('category')->label('分类'),
                TextColumn::make('published_label')->label('月份'),
                TextColumn::make('sort_order')->label('排序'),
            ])
            ->defaultSort('sort_order')
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageSupportDocuments::route('/')];
    }
}

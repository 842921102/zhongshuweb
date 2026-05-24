<?php

namespace App\Filament\Resources\SupportVideos;

use App\Filament\Resources\SupportVideos\Pages\ManageSupportVideos;
use App\Models\SupportVideo;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class SupportVideoResource extends Resource
{
    protected static ?string $model = SupportVideo::class;

    protected static string|UnitEnum|null $navigationGroup = '技术支持';

    protected static ?string $navigationLabel = '教学视频';

    protected static ?string $modelLabel = '教学视频';

    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPlayCircle;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')->label('标题')->required()->maxLength(200),
            FileUpload::make('cover_image')->label('封面图')->image()->directory('support/videos')->disk('public')->columnSpanFull(),
            FileUpload::make('video_url')->label('视频 MP4')->acceptedFileTypes(['video/mp4', 'video/webm'])->directory('support/videos')->disk('public')->required()->columnSpanFull(),
            TextInput::make('duration_label')->label('时长')->placeholder('02:00'),
            TextInput::make('tag')->label('标签')->placeholder('宣传视频'),
            TextInput::make('sort_order')->label('排序')->numeric()->default(0),
            Toggle::make('is_active')->label('启用')->default(true),
            TextInput::make('locale')->label('语言')->default('zh-cn'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')->label('封面')->height(40),
                TextColumn::make('title')->label('标题')->searchable(),
                TextColumn::make('play_count')->label('播放量'),
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
        return ['index' => ManageSupportVideos::route('/')];
    }
}

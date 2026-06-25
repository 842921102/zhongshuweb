<?php

namespace App\Filament\Support;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class OverlayCopyColorFields
{
    public static function section(string $description = '用于首页图片上的标题与副标题文案'): Section
    {
        return Section::make('图卡文案样式')
            ->description($description)
            ->columns(2)
            ->schema([
                TextInput::make('overlay_title_color')
                    ->label('标题颜色')
                    ->placeholder('#FFFFFF')
                    ->maxLength(20)
                    ->helperText('十六进制色值，如 #FFFFFF；留空为默认白色'),
                TextInput::make('overlay_subtitle_color')
                    ->label('副标题颜色')
                    ->placeholder('#E8FFF3')
                    ->maxLength(20)
                    ->helperText('十六进制色值；留空为默认半透明白色'),
            ]);
    }
}

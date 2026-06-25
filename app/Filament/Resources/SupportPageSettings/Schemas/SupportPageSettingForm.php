<?php

namespace App\Filament\Resources\SupportPageSettings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class SupportPageSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('技术支持页面')->columnSpanFull()->tabs([
                Tab::make('SEO & Banner')->schema([
                    Section::make('SEO')->schema([
                        Select::make('locale')->label('语言')->options(['zh-cn' => '中文'])->disabled()->dehydrated(),
                        TextInput::make('meta_title')->label('SEO 标题')->maxLength(160),
                        Textarea::make('meta_description')->label('SEO 描述')->rows(2)->columnSpanFull(),
                        TextInput::make('meta_keywords')->label('关键词')->maxLength(255),
                    ]),
                    Section::make('顶部 Banner')->schema([
                        FileUpload::make('hero_image_pc')->label('PC 横图')->image()->directory('support/hero')->disk('public')->maxSize(10240)->columnSpanFull(),
                        FileUpload::make('hero_image_mobile')->label('手机图（可选）')->image()->directory('support/hero')->disk('public')->maxSize(10240)->columnSpanFull(),
                        TextInput::make('hero_height')->label('高度(px)')->numeric()->default(640),
                        TextInput::make('hero_eyebrow')->label('Banner 眉标')->maxLength(120)->columnSpanFull(),
                        TextInput::make('hero_title')->label('Banner 主标题')->maxLength(200)->columnSpanFull(),
                        Textarea::make('hero_subtitle')->label('Banner 副标题')->rows(2)->columnSpanFull(),
                    ]),
                ]),
                Tab::make('文档区')->schema([
                    TextInput::make('docs_kicker')->label('眉标')->maxLength(120),
                    TextInput::make('docs_title')->label('标题')->maxLength(200),
                    Repeater::make('doc_categories')->label('文档分类 Tab')->simple(TextInput::make('value')->label('分类名')->required())->columnSpanFull(),
                ]),
                Tab::make('视频区')->schema([
                    TextInput::make('videos_kicker')->label('眉标')->maxLength(120),
                    TextInput::make('videos_title')->label('标题')->maxLength(200),
                ]),
                Tab::make('售后表单')->schema([
                    TextInput::make('service_kicker')->label('表单眉标')->maxLength(120),
                    TextInput::make('service_form_title')->label('表单标题')->maxLength(200),
                    Repeater::make('form_topics')->label('咨询主题选项')->simple(TextInput::make('value')->label('主题')->required())->columnSpanFull(),
                ]),
                Tab::make('联系卡片')->schema([
                    Textarea::make('contact_title')->label('左侧卡片标题')->rows(2)->helperText('可换行'),
                    TextInput::make('contact_phone_label')->label('电话标签'),
                    TextInput::make('contact_phone')->label('电话'),
                    TextInput::make('contact_email_label')->label('邮箱标签'),
                    TextInput::make('contact_email')->label('邮箱'),
                    TextInput::make('contact_address_label')->label('地址标签'),
                    Textarea::make('contact_address')->label('地址')->rows(2)->columnSpanFull(),
                ]),
            ]),
        ]);
    }
}

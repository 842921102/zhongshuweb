<?php

namespace App\Filament\Resources\JoinPageSettings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class JoinPageSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('加入我们页面')->columnSpanFull()->tabs([
                Tab::make('SEO & Hero')->schema([
                    Section::make()->columns(2)->schema([
                        Select::make('locale')->label('语言')->options(['zh-cn' => '中文'])->disabled()->dehydrated(),
                        TextInput::make('meta_title')->label('SEO 标题')->maxLength(160),
                        Textarea::make('meta_description')->label('SEO 描述')->rows(2)->columnSpanFull(),
                        TextInput::make('meta_keywords')->label('关键词')->maxLength(255)->columnSpanFull(),
                        FileUpload::make('hero_image')->label('Hero 背景（PC / 默认）')->image()->directory('join-us')->disk('public'),
                        FileUpload::make('hero_image_mobile')->label('Hero 背景（手机端，可选）')->image()->directory('join-us')->disk('public')
                            ->helperText('留空则小屏使用 PC 图')->columnSpanFull(),
                        TextInput::make('hero_eyebrow')->label('Hero 眉标')->maxLength(120),
                        TextInput::make('hero_title')->label('Hero 标题（主句）')->maxLength(200)->columnSpanFull(),
                        TextInput::make('hero_title_highlight')->label('Hero 标题高亮')->maxLength(120)->columnSpanFull(),
                        Textarea::make('hero_description')->label('Hero 描述')->rows(3)->columnSpanFull(),
                        TextInput::make('hero_cta_primary')->label('主按钮文案')->maxLength(40),
                        TextInput::make('hero_cta_secondary')->label('次按钮文案')->maxLength(40),
                    ]),
                ]),
                Tab::make('区块标题')->schema([
                    Section::make('为什么加入')->columns(2)->schema([
                        TextInput::make('why_kicker')->label('眉标'),
                        TextInput::make('why_title')->label('标题'),
                        Textarea::make('why_subtitle')->label('副标题')->rows(2)->columnSpanFull(),
                    ]),
                    Section::make('文化')->columns(2)->schema([
                        FileUpload::make('culture_image')->label('文化区大图（PC / 默认）')->image()->directory('join-us')->disk('public'),
                        FileUpload::make('culture_image_mobile')->label('文化区大图（手机端，可选）')->image()->directory('join-us')->disk('public')
                            ->columnSpanFull(),
                        TextInput::make('culture_kicker')->label('眉标'),
                        TextInput::make('culture_title')->label('标题'),
                        Textarea::make('culture_subtitle')->label('副标题')->rows(2)->columnSpanFull(),
                    ]),
                    Section::make('岗位 & 流程 & 福利')->columns(2)->schema([
                        TextInput::make('jobs_kicker')->label('岗位眉标'),
                        TextInput::make('jobs_title')->label('岗位标题'),
                        Textarea::make('jobs_subtitle')->label('岗位副标题')->rows(2)->columnSpanFull(),
                        TextInput::make('all_jobs_label')->label('「全部岗位」')->default('全部岗位'),
                        TextInput::make('process_kicker')->label('流程眉标'),
                        TextInput::make('process_title')->label('流程标题'),
                        Textarea::make('process_subtitle')->label('流程副标题')->rows(2)->columnSpanFull(),
                        TextInput::make('welfare_kicker')->label('福利眉标'),
                        TextInput::make('welfare_title')->label('福利标题'),
                        Textarea::make('welfare_subtitle')->label('福利副标题')->rows(2)->columnSpanFull(),
                    ]),
                ]),
                Tab::make('联系投递')->schema([
                    Section::make()->columns(2)->schema([
                        TextInput::make('contact_kicker')->label('眉标'),
                        TextInput::make('contact_title')->label('标题')->columnSpanFull(),
                        Textarea::make('contact_subtitle')->label('说明')->rows(3)->columnSpanFull(),
                        TextInput::make('contact_email')->label('投递邮箱')->email(),
                        TextInput::make('contact_phone')->label('联系电话'),
                        TextInput::make('contact_locations')->label('工作地点')->columnSpanFull(),
                        TextInput::make('contact_email_subject_tip')->label('邮件标题提示')->columnSpanFull(),
                        TextInput::make('apply_label')->label('岗位「立即投递」')->default('立即投递'),
                        TextInput::make('send_resume_label')->label('「发送简历」邮件按钮')->default('发送简历'),
                        TextInput::make('form_title')->label('表单标题')->default('在线投递简历')->columnSpanFull(),
                        TextInput::make('form_submit_label')->label('表单提交按钮')->default('提交简历'),
                        TextInput::make('form_success_message')->label('提交成功提示')->columnSpanFull(),
                        TextInput::make('form_error_message')->label('提交失败提示')->columnSpanFull(),
                    ]),
                ]),
            ]),
        ]);
    }
}

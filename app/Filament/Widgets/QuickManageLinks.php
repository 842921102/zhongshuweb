<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Articles\ArticleResource;
use App\Filament\Resources\Banners\BannerResource;
use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Resources\HomeSections\HomeSectionResource;
use App\Filament\Resources\JoinApplications\JoinApplicationResource;
use App\Filament\Resources\ProductConsultations\ProductConsultationResource;
use App\Filament\Resources\Products\ProductResource;
use App\Filament\Resources\SiteNavMenus\SiteNavMenuResource;
use App\Filament\Resources\SiteSettings\SiteSettingResource;
use App\Filament\Resources\SupportServiceRequests\SupportServiceRequestResource;
use App\Services\AdminSubmissionStats;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class QuickManageLinks extends Widget
{
    protected static ?int $sort = 3;

    protected string $view = 'filament.widgets.quick-manage-links';

    protected int|string|array $columnSpan = 'full';

    /**
     * @return array<int, array{label: string, url: string, icon: string}>
     */
    public function getLinks(): array
    {
        $links = [];

        if (AdminSubmissionStats::userCanView(auth()->user(), AdminSubmissionStats::MODULE_JOIN)) {
            $links[] = [
                'label' => '简历投递',
                'url' => JoinApplicationResource::getUrl(),
                'icon' => 'inbox',
            ];
        }

        if (AdminSubmissionStats::userCanView(auth()->user(), AdminSubmissionStats::MODULE_PRODUCT)) {
            $links[] = [
                'label' => '产品咨询',
                'url' => ProductConsultationResource::getUrl(),
                'icon' => 'chat',
            ];
        }

        if (AdminSubmissionStats::userCanView(auth()->user(), AdminSubmissionStats::MODULE_SUPPORT)) {
            $links[] = [
                'label' => '售后申请',
                'url' => SupportServiceRequestResource::getUrl(),
                'icon' => 'support',
            ];
        }

        return array_merge($links, [
            [
                'label' => '轮播图',
                'url' => BannerResource::getUrl(),
                'icon' => 'photo',
            ],
            [
                'label' => '首页模块',
                'url' => HomeSectionResource::getUrl(),
                'icon' => 'layout',
            ],
            [
                'label' => '菜单管理',
                'url' => SiteNavMenuResource::getUrl(),
                'icon' => 'menu',
            ],
            [
                'label' => '产品分类',
                'url' => CategoryResource::getUrl(),
                'icon' => 'folder',
            ],
            [
                'label' => '产品列表',
                'url' => ProductResource::getUrl(),
                'icon' => 'cube',
            ],
            [
                'label' => '新闻资讯',
                'url' => ArticleResource::getUrl(),
                'icon' => 'news',
            ],
            [
                'label' => '站点设置',
                'url' => SiteSettingResource::getUrl(),
                'icon' => 'cog',
            ],
        ]);
    }

    public function getUserName(): string
    {
        $user = Auth::user();

        return $user?->name ?? '管理员';
    }
}

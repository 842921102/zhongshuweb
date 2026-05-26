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
use Illuminate\Support\Facades\Gate;

class QuickManageLinks extends Widget
{
    protected static ?int $sort = 3;

    protected string $view = 'filament.widgets.quick-manage-links';

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        $widget = new static;

        return count($widget->getLinks()) > 0;
    }

    /**
     * @return array<int, array{label: string, url: string, icon: string}>
     */
    public function getLinks(): array
    {
        $user = auth()->user();
        $links = [];

        if (! $user) {
            return $links;
        }

        if (AdminSubmissionStats::userCanView($user, AdminSubmissionStats::MODULE_JOIN)) {
            $links[] = [
                'label' => '简历投递',
                'url' => JoinApplicationResource::getUrl(),
                'icon' => 'inbox',
            ];
        }

        if (AdminSubmissionStats::userCanView($user, AdminSubmissionStats::MODULE_PRODUCT)) {
            $links[] = [
                'label' => '产品咨询',
                'url' => ProductConsultationResource::getUrl(),
                'icon' => 'chat',
            ];
        }

        if (AdminSubmissionStats::userCanView($user, AdminSubmissionStats::MODULE_SUPPORT)) {
            $links[] = [
                'label' => '售后申请',
                'url' => SupportServiceRequestResource::getUrl(),
                'icon' => 'support',
            ];
        }

        $candidates = [
            ['label' => '轮播图', 'resource' => BannerResource::class, 'icon' => 'photo'],
            ['label' => '首页模块', 'resource' => HomeSectionResource::class, 'icon' => 'layout'],
            ['label' => '菜单管理', 'resource' => SiteNavMenuResource::class, 'icon' => 'menu'],
            ['label' => '产品分类', 'resource' => CategoryResource::class, 'icon' => 'folder'],
            ['label' => '产品列表', 'resource' => ProductResource::class, 'icon' => 'cube'],
            ['label' => '新闻资讯', 'resource' => ArticleResource::class, 'icon' => 'news'],
            ['label' => '站点设置', 'resource' => SiteSettingResource::class, 'icon' => 'cog'],
        ];

        foreach ($candidates as $item) {
            $model = $item['resource']::getModel();

            if (Gate::forUser($user)->check('viewAny', $model)) {
                $links[] = [
                    'label' => $item['label'],
                    'url' => $item['resource']::getUrl(),
                    'icon' => $item['icon'],
                ];
            }
        }

        return $links;
    }
}

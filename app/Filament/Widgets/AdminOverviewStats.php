<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Articles\ArticleResource;
use App\Filament\Resources\Banners\BannerResource;
use App\Filament\Resources\CaseStudies\CaseStudyResource;
use App\Filament\Resources\Products\ProductResource;
use App\Models\Article;
use App\Models\Banner;
use App\Models\CaseStudy;
use App\Models\Product;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Gate;

class AdminOverviewStats extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        return Gate::forUser($user)->check('viewAny', Banner::class)
            || Gate::forUser($user)->check('viewAny', Product::class)
            || Gate::forUser($user)->check('viewAny', CaseStudy::class)
            || Gate::forUser($user)->check('viewAny', Article::class);
    }

    protected function getStats(): array
    {
        $user = auth()->user();
        $stats = [];

        if ($user && Gate::forUser($user)->check('viewAny', Banner::class)) {
            $stats[] = Stat::make('轮播图', (string) Banner::count())
                ->description('首页 Banner')
                ->descriptionIcon(Heroicon::OutlinedPhoto)
                ->color('primary')
                ->url(BannerResource::getUrl());
        }

        if ($user && Gate::forUser($user)->check('viewAny', Product::class)) {
            $stats[] = Stat::make('产品', (string) Product::count())
                ->description('已录入产品')
                ->descriptionIcon(Heroicon::OutlinedCube)
                ->color('info')
                ->url(ProductResource::getUrl());
        }

        if ($user && Gate::forUser($user)->check('viewAny', CaseStudy::class)) {
            $stats[] = Stat::make('案例', (string) CaseStudy::count())
                ->description('工程案例')
                ->descriptionIcon(Heroicon::OutlinedBriefcase)
                ->color('success')
                ->url(CaseStudyResource::getUrl());
        }

        if ($user && Gate::forUser($user)->check('viewAny', Article::class)) {
            $stats[] = Stat::make('新闻', (string) Article::count())
                ->description('新闻资讯')
                ->descriptionIcon(Heroicon::OutlinedNewspaper)
                ->color('warning')
                ->url(ArticleResource::getUrl());
        }

        return $stats;
    }
}

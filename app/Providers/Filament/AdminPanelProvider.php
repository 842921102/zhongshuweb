<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Dashboard;
use Filament\Enums\ThemeMode;
use Filament\FontProviders\GoogleFontProvider;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->brandName('众鼠CMS管理系统')
            ->colors([
                'primary' => Color::hex('#2BA471'),
            ])
            ->font('Noto Sans SC', provider: GoogleFontProvider::class)
            ->defaultThemeMode(ThemeMode::Light)
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups([
                $this->collapsedNavGroup('官网管理', Heroicon::OutlinedGlobeAlt),
                $this->collapsedNavGroup('产品管理', Heroicon::OutlinedCube),
                $this->collapsedNavGroup('案例管理', Heroicon::OutlinedBriefcase),
                $this->collapsedNavGroup('新闻管理', Heroicon::OutlinedNewspaper),
                $this->collapsedNavGroup('关于我们', Heroicon::OutlinedBuildingOffice2),
                $this->collapsedNavGroup('技术支持', Heroicon::OutlinedWrenchScrewdriver),
                $this->collapsedNavGroup('加入我们', Heroicon::OutlinedUserPlus),
                $this->collapsedNavGroup('内容管理', Heroicon::OutlinedDocumentText),
                $this->collapsedNavGroup('系统设置', Heroicon::OutlinedCog6Tooth),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }

    private function collapsedNavGroup(string $label, Heroicon $icon): NavigationGroup
    {
        return NavigationGroup::make($label)
            ->icon($icon)
            ->collapsed();
    }
}

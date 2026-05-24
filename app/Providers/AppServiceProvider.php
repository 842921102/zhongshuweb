<?php

namespace App\Providers;

use App\Support\AdminPermissionRegistry;
use App\View\Composers\SiteLayoutComposer;
use Filament\Facades\Filament;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if ($this->app->environment('local') && ! $this->app->runningInConsole()) {
            $request = request();
            if ($request->hasHeader('Host')) {
                URL::forceRootUrl($request->getScheme().'://'.$request->getHttpHost());
            }
        }

        Filament::serving(function (): void {
            app()->setLocale('zh_CN');

            if (app()->environment('local')) {
                $request = request();
                if ($request->hasHeader('Host')) {
                    URL::forceRootUrl($request->getScheme().'://'.$request->getHttpHost());
                }
            }
        });

        Gate::before(function ($user, string $ability, array $arguments) {
            if (! $user || ! method_exists($user, 'isSuperAdmin')) {
                return null;
            }

            if ($user->isSuperAdmin()) {
                return true;
            }

            $subject = $arguments[0] ?? null;
            $modelClass = is_object($subject) ? $subject::class : (is_string($subject) && class_exists($subject) ? $subject : null);

            if (! $modelClass) {
                return null;
            }

            $module = AdminPermissionRegistry::moduleForModel($modelClass);

            if (! $module) {
                return null;
            }

            $permission = AdminPermissionRegistry::permissionName(
                $module,
                AdminPermissionRegistry::mapPolicyAbility($ability),
            );

            if (! AdminPermissionRegistry::permissionExists($permission)) {
                return null;
            }

            return $user->hasPermission($permission);
        });

        FilamentView::registerRenderHook(
            PanelsRenderHook::STYLES_AFTER,
            fn (): string => Blade::render('<link rel="stylesheet" href="{{ $url }}">', [
                'url' => asset('css/admin-panel.css'),
            ]),
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::SIDEBAR_NAV_START,
            fn (): string => view('filament.partials.sidebar-clock')->render(),
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::SCRIPTS_AFTER,
            fn (): string => Blade::render('<script src="{{ $url }}" defer></script>', [
                'url' => asset('js/admin-sidebar-nav.js'),
            ]),
        );

        View::composer('layouts.home', SiteLayoutComposer::class);
    }
}

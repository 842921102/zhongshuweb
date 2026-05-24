<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use App\View\Composers\SiteLayoutComposer;
use Illuminate\Support\Facades\Blade;
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

        FilamentView::registerRenderHook(
            PanelsRenderHook::STYLES_AFTER,
            fn (): string => Blade::render('<link rel="stylesheet" href="{{ $url }}">', [
                'url' => asset('css/admin-panel.css'),
            ]),
        );

        View::composer('layouts.home', SiteLayoutComposer::class);
    }
}

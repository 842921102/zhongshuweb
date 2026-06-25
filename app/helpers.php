<?php

use App\Support\MediaUrl;
use Illuminate\Support\Arr;

if (! function_exists('versioned_asset')) {
    function versioned_asset(string $path): string
    {
        $relative = ltrim($path, '/');
        $version = \App\Support\ReleaseInfo::version();
        $fullPath = public_path($relative);

        if (is_file($fullPath)) {
            $version .= '.'.filemtime($fullPath);
        }

        return asset($path).'?v='.rawurlencode($version);
    }
}

if (! function_exists('media_url')) {
    function media_url(mixed $path, ?string $fallback = null): ?string
    {
        return MediaUrl::resolve($path, $fallback);
    }
}

if (! function_exists('responsive_media')) {
    /**
     * @return array{src: ?string, pc: ?string, mobile: ?string, use_picture: bool}
     */
    function responsive_media(?string $pc, ?string $mobile = null, ?string $fallback = null): array
    {
        return \App\Support\ResponsiveMedia::resolve($pc, $mobile, $fallback);
    }
}

if (! function_exists('responsive_bg_style')) {
    /** CSS 变量：配合 .has-responsive-bg 在 ≤968px 切换背景图 */
    function responsive_bg_style(?string $pc, ?string $mobile = null, ?string $fallback = null): string
    {
        $media = responsive_media($pc, $mobile, $fallback);
        if (blank($media['src'])) {
            return '';
        }

        $pcUrl = str_replace("'", "\\'", (string) $media['pc']);
        $mobileUrl = str_replace("'", "\\'", (string) ($media['mobile'] ?? $media['pc']));

        return "--responsive-bg-pc:url('{$pcUrl}');--responsive-bg-mobile:url('{$mobileUrl}');";
    }
}

if (! function_exists('upload_disk')) {
    function upload_disk(): string
    {
        return \App\Services\CosStorageService::uploadDisk();
    }
}

if (! function_exists('current_lang')) {
    function current_lang(string $fallback = 'zh-cn'): string
    {
        $defaultLocale = (string) config('site.frontend_locale', $fallback);

        if (! config('site.locale_switcher_enabled', false)) {
            return $defaultLocale;
        }

        try {
            $lang = request()->query('lang');
        } catch (Throwable) {
            $lang = null;
        }

        return filled($lang) ? (string) $lang : $defaultLocale;
    }
}

if (! function_exists('lang_query')) {
    function lang_query(?string $locale = null): array
    {
        $locale = $locale ?: current_lang();

        return filled($locale) && $locale !== 'zh-cn'
            ? ['lang' => $locale]
            : [];
    }
}

if (! function_exists('rebuild_url')) {
    function rebuild_url(array $parts, array $query = []): string
    {
        $scheme = Arr::get($parts, 'scheme');
        $host = Arr::get($parts, 'host');
        $port = Arr::get($parts, 'port');
        $user = Arr::get($parts, 'user');
        $pass = Arr::get($parts, 'pass');
        $path = Arr::get($parts, 'path', '');
        $fragment = Arr::get($parts, 'fragment');

        $authority = '';
        if ($host !== null) {
            $credentials = $user !== null ? $user.($pass !== null ? ':'.$pass : '').'@' : '';
            $authority = $credentials.$host.($port !== null ? ':'.$port : '');
        }

        $rebuilt = '';
        if ($scheme !== null) {
            $rebuilt .= $scheme.'://';
        } elseif ($authority !== '') {
            $rebuilt .= '//';
        }

        $rebuilt .= $authority.$path;

        if ($query !== []) {
            $rebuilt .= '?'.http_build_query($query);
        }

        if ($fragment !== null && $fragment !== '') {
            $rebuilt .= '#'.$fragment;
        }

        return $rebuilt;
    }
}

if (! function_exists('with_lang_url')) {
    function with_lang_url(string $url, ?string $locale = null): string
    {
        $parts = parse_url($url);
        if ($parts === false) {
            return $url;
        }

        parse_str($parts['query'] ?? '', $query);
        unset($query['lang']);
        $query = array_merge($query, lang_query($locale));

        return rebuild_url($parts, $query);
    }
}

if (! function_exists('localized_route')) {
    function localized_route(string $name, array $parameters = [], ?string $locale = null, bool $absolute = true): string
    {
        return route($name, array_merge(lang_query($locale), $parameters), $absolute);
    }
}

if (! function_exists('localized_url')) {
    function localized_url(string $path = '/', ?string $locale = null): string
    {
        if (
            $path === '#'
            || str_starts_with($path, '#')
            || str_starts_with($path, 'http://')
            || str_starts_with($path, 'https://')
            || str_starts_with($path, 'mailto:')
            || str_starts_with($path, 'tel:')
            || str_starts_with($path, 'javascript:')
        ) {
            return $path;
        }

        $url = $path === '/'
            ? url('/')
            : (str_starts_with($path, '/') ? url($path) : url('/'.$path));

        return with_lang_url($url, $locale);
    }
}

if (! function_exists('current_url_for_lang')) {
    function current_url_for_lang(string $locale = 'zh-cn'): string
    {
        try {
            return with_lang_url(request()->fullUrl(), $locale);
        } catch (Throwable) {
            return localized_url('/', $locale);
        }
    }
}

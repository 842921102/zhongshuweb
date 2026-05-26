<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', $locale ?? 'zh-cn') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $siteDescription ?: '众鼠科技 - 全场景智能清洁设备服务商' }}">
    <title>{{ $siteName ?? '众鼠科技' }}</title>
    <link rel="stylesheet" href="{{ asset('home-assets/home-common.css') }}">
    <link rel="stylesheet" href="{{ asset('home-assets/home.css') }}">
    <link rel="stylesheet" href="{{ asset('home-assets/overlay-cleanup.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home-fullpage.css') }}">
    <style>
        :root {
            --theme-default-color: #00A85A;
            --theme-default-font-color: #00A85A;
            --theme-default-button-text-color: #ffffff;
            --theme-default-button-border-color: transparent;
        }
    </style>
    @stack('head')
    <link rel="stylesheet" href="{{ asset('css/site-responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('css/site-typography.css') }}">
    <link rel="stylesheet" href="{{ asset('css/site-footer.css') }}">
</head>
<body class="site-layout has-immersive-header @if(request()->routeIs('home')) is-home-index home-page @endif @yield('body_class')">
<div class="site-layout @if(request()->routeIs('home')) is-home-index home-page @endif">
    @include('home.partials.header')
    <div class="site-header-spacer" aria-hidden="true"></div>
    <main class="site-main new_xz">
        @yield('content')
    </main>
    @include('home.partials.footer')
</div>
<script src="{{ asset('home-assets/site-layout.js') }}" defer></script>
<script src="{{ asset('home-assets/home.js') }}" defer></script>
@stack('scripts')
</body>
</html>

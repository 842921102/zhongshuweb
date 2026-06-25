<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', $locale ?? 'zh-cn') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @stack('head')
    <link rel="stylesheet" href="{{ versioned_asset('home-assets/home-common.css') }}">
    @if(request()->routeIs('home'))
        <link rel="stylesheet" href="{{ versioned_asset('home-assets/home.css') }}">
        <link rel="stylesheet" href="{{ versioned_asset('css/home-fullpage.css') }}">
    @endif
    <link rel="stylesheet" href="{{ versioned_asset('home-assets/overlay-cleanup.css') }}">
    <style>
        :root {
            --theme-default-color: #00A85A;
            --theme-default-font-color: #00A85A;
            --theme-default-button-text-color: #ffffff;
            --theme-default-button-border-color: transparent;
        }
    </style>
    @stack('preload')
    <link rel="stylesheet" href="{{ versioned_asset('css/site-responsive.css') }}">
    <link rel="stylesheet" href="{{ versioned_asset('css/site-typography.css') }}">
    <link rel="stylesheet" href="{{ versioned_asset('css/site-footer.css') }}">
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
<script src="{{ versioned_asset('home-assets/site-layout.js') }}" defer></script>
@if(request()->routeIs('home'))
    <script src="{{ versioned_asset('home-assets/home.js') }}" defer></script>
@endif
@stack('scripts')
</body>
</html>

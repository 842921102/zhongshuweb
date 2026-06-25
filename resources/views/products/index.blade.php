@extends('layouts.home')

@section('body_class', 'product-page news-page')

@php
    $pageTitle = $pageSettings->meta_title ?: ('产品中心 - '.$siteName);
    $bannerPc = media_url($pageSettings->banner_image_pc);
    $bannerMobile = media_url($pageSettings->banner_image_mobile) ?: $bannerPc;
    $bannerPoster = media_url($pageSettings->banner_video_poster) ?: $bannerPc;
    $bannerVideo = media_url($pageSettings->banner_video_url);
@endphp

@push('head')
    <title>{{ $pageTitle }}</title>
    @if($bannerPc)
        <link rel="preload" as="image" href="{{ $bannerPc }}">
    @endif
    @if($pageSettings->meta_description)
        <meta name="description" content="{{ $pageSettings->meta_description }}">
    @endif
    @if($pageSettings->meta_keywords)
        <meta name="keywords" content="{{ $pageSettings->meta_keywords }}">
    @endif
    <link rel="stylesheet" href="{{ versioned_asset('css/product-banner.css') }}">
    <link rel="stylesheet" href="{{ versioned_asset('css/product.css') }}">
@endpush

@push('scripts')
    <script id="productPageData" type="application/json">@json($productPageJson)</script>
    <script>
        window.productIndexUrl = @json(route('products.index', request()->only('lang')));
    </script>
    <script src="{{ versioned_asset('js/product-banner.js') }}" defer></script>
    <script src="{{ versioned_asset('js/product.js') }}" defer></script>
@endpush

@section('content')
<div class="product-main new_xz">
    <section class="product-hero product-hero--banner-only">
        @include('products.partials.banner', [
            'imagePc' => $bannerPc,
            'imageMobile' => $bannerMobile,
            'videoUrl' => $pageSettings->isBannerVideo() ? $bannerVideo : null,
            'poster' => $bannerPoster,
            'ariaLabel' => '产品中心 Banner',
            'videoId' => 'productListBannerVideo',
        ])
    </section>

    @if($catalogTabsEnabled && $catalogTabs->isNotEmpty())
    <div class="product-tabs-bar">
        <div class="product-shell">
            <div class="product-catalog-tabs product-catalog-tabs--root" id="productCatalogTabs" role="tablist" aria-label="产品一级分类">
                <button type="button"
                        class="product-catalog-tab {{ $activeRoot === 'all' ? 'is-active' : '' }}"
                        data-root="all"
                        role="tab"
                        aria-selected="{{ $activeRoot === 'all' ? 'true' : 'false' }}">{{ $labels['all'] }}</button>
                @foreach($catalogTabs as $root)
                    <button type="button"
                            class="product-catalog-tab {{ $activeRoot === $root->domKey() ? 'is-active' : '' }}"
                            data-root="{{ $root->domKey() }}"
                            role="tab"
                            aria-selected="{{ $activeRoot === $root->domKey() ? 'true' : 'false' }}">{{ $root->name }}</button>
                @endforeach
            </div>
        </div>
    </div>
    <div class="product-tabs-bar product-tabs-bar--sub" id="productCatalogSubTabsBar" @if($activeRoot === 'all' || $catalogSubTabs->isEmpty()) hidden @endif>
        <div class="product-shell">
            <div class="product-catalog-tabs product-catalog-tabs--sub" id="productCatalogSubTabs" role="tablist" aria-label="产品二级分类">
                @if($activeRoot !== 'all')
                    <button type="button"
                            class="product-catalog-tab product-catalog-tab--sub {{ $activeSub === 'all' ? 'is-active' : '' }}"
                            data-sub="all"
                            role="tab"
                            aria-selected="{{ $activeSub === 'all' ? 'true' : 'false' }}">{{ $labels['all'] }}</button>
                    @foreach($catalogSubTabs as $child)
                        <button type="button"
                                class="product-catalog-tab product-catalog-tab--sub {{ $activeSub === $child->domKey() ? 'is-active' : '' }}"
                                data-sub="{{ $child->domKey() }}"
                                role="tab"
                                aria-selected="{{ $activeSub === $child->domKey() ? 'true' : 'false' }}">{{ $child->name }}</button>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    @endif

    <section class="product-page-body" id="productCatalog">
        <div class="product-shell">
            <div class="product-catalog-grid" id="productCatalogGrid">
                @forelse($catalogProducts as $i => $product)
                    @include('products.partials.card', ['product' => $product, 'delay' => $i * 60, 'cardClass' => 'product-catalog-card'])
                @empty
                    <p class="product-empty">{{ $labels['catalog_empty'] }}</p>
                @endforelse
            </div>
        </div>
    </section>
</div>
@endsection

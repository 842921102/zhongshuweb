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
    @if($pageSettings->meta_description)
        <meta name="description" content="{{ $pageSettings->meta_description }}">
    @endif
    @if($pageSettings->meta_keywords)
        <meta name="keywords" content="{{ $pageSettings->meta_keywords }}">
    @endif
    <link rel="stylesheet" href="{{ asset('css/product-banner.css') }}">
    <link rel="stylesheet" href="{{ asset('css/product.css') }}">
@endpush

@push('scripts')
    <script id="productPageData" type="application/json">@json($productPageJson)</script>
    <script>
        window.productIndexUrl = @json(route('products.index', request()->only('lang')));
    </script>
    <script src="{{ asset('js/product-banner.js') }}" defer></script>
    <script src="{{ asset('js/product.js') }}" defer></script>
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

    <div class="product-tabs-bar">
        <div class="product-shell">
            <div class="product-catalog-tabs" id="productCatalogTabs" role="tablist" aria-label="产品分类筛选">
                <button type="button"
                        class="product-catalog-tab {{ str_starts_with($activeCatalogTab, 'all:') ? 'is-active' : '' }}"
                        data-category="all:{{ $activeSeriesKey }}"
                        role="tab"
                        aria-selected="{{ str_starts_with($activeCatalogTab, 'all:') ? 'true' : 'false' }}">{{ $labels['all'] }}</button>
                @foreach($activeRoot?->children ?? [] as $child)
                    <button type="button"
                            class="product-catalog-tab {{ $activeCatalogTab === $child->domKey() ? 'is-active' : '' }}"
                            data-category="{{ $child->domKey() }}"
                            role="tab"
                            aria-selected="{{ $activeCatalogTab === $child->domKey() ? 'true' : 'false' }}">{{ $child->name }}</button>
                @endforeach
            </div>
        </div>
    </div>

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

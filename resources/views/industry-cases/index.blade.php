@extends('layouts.home')

@section('body_class', 'industry-cases-page has-immersive-header')

@section('content')
@php
    $bannerPc = media_url($pageSettings->banner_image_pc);
    $bannerMobile = media_url($pageSettings->banner_image_mobile) ?: $bannerPc;
    $bannerHeight = $pageSettings->banner_height ?: 640;
    $videoUrl = trim((string) $pageSettings->banner_video_url);
    $ctaLabel = $pageSettings->detail_button_text ?: '查看方案';
    $pageTitle = $pageSettings->meta_title ?: ($pageSettings->page_title.' - '.$siteName);
@endphp

@push('head')
    <title>{{ $pageTitle }}</title>
    @if($pageSettings->meta_description)
        <meta name="description" content="{{ $pageSettings->meta_description }}">
    @endif
    <link rel="stylesheet" href="{{ asset('css/industry-cases.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/industry-cases.js') }}" defer></script>
@endpush

<div class="ic-main new_xz">
    <section class="ic-hero" style="--ic-banner-height: {{ $bannerHeight }}px" aria-label="{{ $pageSettings->page_title }}">
        <div class="ic-hero__media" aria-hidden="true">
            @if($videoUrl)
                <video class="ic-hero__video" autoplay muted loop playsinline preload="metadata">
                    <source src="{{ $videoUrl }}" type="video/mp4">
                </video>
            @elseif($bannerPc || $pageSettings->banner_image_pc)
                <x-responsive-bg
                    :pc="$pageSettings->banner_image_pc"
                    :mobile="$pageSettings->banner_image_mobile"
                    class="ic-hero__bg"
                />
            @else
                <div class="ic-hero__bg ic-hero__bg--fallback"></div>
            @endif
        </div>
        <div class="ic-hero__overlay" aria-hidden="true"></div>
        <div class="ic-hero__container">
            <div class="ic-hero__content">
                <h1>{{ $pageSettings->page_title }}</h1>
                @if($pageSettings->page_subtitle)
                    <p>{{ $pageSettings->page_subtitle }}</p>
                @endif
            </div>
        </div>
    </section>

    @if($solutions->isNotEmpty())
        <div class="ic-tabs-wrap" data-ic-tabs-nav>
            <div class="ic-tabs-inner">
                <div class="ic-tabs" data-ic-tabs role="tablist" aria-label="行业分类">
                    <span class="ic-tabs__indicator" data-ic-indicator aria-hidden="true"></span>
                    @foreach($solutions as $index => $item)
                        <button
                            type="button"
                            class="ic-tabs__btn {{ $index === 0 ? 'is-active' : '' }}"
                            data-ic-tab
                            role="tab"
                            aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                            aria-controls="{{ $item->anchorId() }}"
                        >{{ $item->title }}</button>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="ic-body">
    @if($solutions->isEmpty())
        <p class="ic-empty">暂无解决方案，请在后台「解决方案列表」中添加内容。</p>
    @else
        <div class="ic-list">
            @foreach($solutions as $item)
                <article class="ic-block" id="{{ $item->anchorId() }}" data-ic-block>
                    <div class="ic-block__media-wrap {{ $item->cover_image ? '' : 'ic-block__media-wrap--empty' }}">
                        @if($item->cover_image)
                            <x-responsive-image
                                :pc="$item->cover_image"
                                :mobile="$item->cover_image_mobile"
                                :alt="$item->title"
                                class="ic-block__img"
                                loading="lazy"
                            />
                        @endif
                    </div>
                    <aside class="ic-block__card">
                        <h2>{{ $item->title }}</h2>
                        @if($item->cardSummary())
                            <p>{{ $item->cardSummary() }}</p>
                        @endif
                        <a class="ic-block__cta" href="{{ $item->url() }}">{{ $item->detail_button_text ?: $ctaLabel }}</a>
                    </aside>
                </article>
            @endforeach
        </div>
    @endif
    </div>
</div>
@endsection

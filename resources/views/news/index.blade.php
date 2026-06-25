@extends('layouts.home')

@section('body_class', 'news-page')

@php
    $bannerPc = media_url($pageSettings->banner_image_pc);
    $bannerMobile = media_url($pageSettings->banner_image_mobile) ?: $bannerPc;
    $heroHeight = $pageSettings->banner_height ?: 640;
    $pageTitle = $pageSettings->meta_title ?: ('新闻资讯 - '.$siteName);
@endphp

@push('head')
    <title>{{ $pageTitle }}</title>
    @if($pageSettings->meta_description)
        <meta name="description" content="{{ $pageSettings->meta_description }}">
    @endif
    @if($pageSettings->meta_keywords)
        <meta name="keywords" content="{{ $pageSettings->meta_keywords }}">
    @endif
    <link rel="stylesheet" href="{{ versioned_asset('css/news.css') }}">
@endpush

@push('scripts')
    <script src="{{ versioned_asset('js/news.js') }}" defer></script>
@endpush

@section('content')
<div class="news-main">
    <section class="news-hero site-page-banner" style="--site-page-banner-height: {{ $heroHeight }}px">
        <x-responsive-bg
            :pc="$pageSettings->banner_image_pc"
            :mobile="$pageSettings->banner_image_mobile"
            class="news-hero-media"
            aria-hidden="true"
        />
        <div class="news-hero-container"></div>
    </section>

    <section class="news-content">
        <div class="news-content-bg" aria-hidden="true"></div>
        <div class="news-shell">
            @if($categories->isNotEmpty())
                <nav class="news-category-tabs" aria-label="新闻分类">
                    <a href="{{ route('news.index', request()->only('lang')) }}"
                       class="news-category-tab {{ $activeCategorySlug === 'all' ? 'is-active' : '' }}"
                       @if($activeCategorySlug === 'all') aria-current="page" @endif>
                        {{ $allCategoryLabel }}
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('news.index', array_merge(request()->only('lang'), ['category' => $cat->slug])) }}"
                           class="news-category-tab {{ $activeCategorySlug === $cat->slug ? 'is-active' : '' }}"
                           @if($activeCategorySlug === $cat->slug) aria-current="page" @endif>
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </nav>
            @endif

            @if($featuredArticle)
                <article class="news-featured-card">
                    <a href="{{ $featuredArticle->url() }}" class="news-block-link" aria-labelledby="news-featured-title">
                        @if($featuredArticle->cover_image || $featuredArticle->cover_image_mobile)
                            <div class="news-featured-media">
                                <x-responsive-image
                                    :pc="$featuredArticle->cover_image"
                                    :mobile="$featuredArticle->cover_image_mobile"
                                    decorative
                                    decoding="async"
                                />
                            </div>
                        @endif
                        <div class="news-featured-body">
                            @if($featuredArticle->badgeLabel())
                                <div class="news-badge">
                                    <span class="news-badge-dot"></span>
                                    <span>{{ $featuredArticle->badgeLabel() }}</span>
                                </div>
                            @endif
                            <div class="news-meta news-meta-featured">
                                <img src="{{ asset('home-assets/home-news-calendar.svg') }}" alt="" aria-hidden="true">
                                <span>{{ $featuredArticle->displayDate() }}</span>
                            </div>
                            <h2 id="news-featured-title" class="news-featured-title">{{ $featuredArticle->title }}</h2>
                            @if($featuredArticle->listSummary())
                                <p class="news-featured-desc">{{ $featuredArticle->listSummary(200) }}</p>
                            @endif
                            @include('news.partials.readmore', ['label' => $readMoreLabel, 'class' => 'news-readmore-featured'])
                        </div>
                    </a>
                </article>
            @endif

            @if($articles->isNotEmpty())
                <div class="news-grid">
                    @foreach($articles as $article)
                        @include('news.partials.card', ['article' => $article, 'readMoreLabel' => $readMoreLabel])
                    @endforeach
                </div>

                @if($articles->hasPages())
                    <nav class="news-pagination" aria-label="新闻分页">
                        {{ $articles->onEachSide(1)->links('news.partials.pagination') }}
                    </nav>
                @endif
            @elseif(! $featuredArticle)
                <p class="news-empty">暂无新闻资讯</p>
            @endif
        </div>
    </section>
</div>
@endsection

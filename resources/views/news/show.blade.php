@extends('layouts.home')

@section('body_class', 'news-page newsdetail-page')

@php
    $pageTitle = $article->seo_title ?: ($article->title.' - 新闻资讯 - '.$siteName);
    $metaDescription = $article->seo_description ?: $article->listSummary(160);
    $coverUrl = media_url($article->cover_image);
    $bannerHeight = $pageSettings->banner_height ?: 640;
@endphp

@push('head')
    <title>{{ $pageTitle }}</title>
    @if($metaDescription)
        <meta name="description" content="{{ $metaDescription }}">
    @endif
    <link rel="stylesheet" href="{{ versioned_asset('css/news.css') }}">
@endpush

@push('scripts')
    <script src="{{ versioned_asset('js/news.js') }}" defer></script>
@endpush

@section('content')
<div class="news-main newsdetail-main">
    @if($article->cover_image || $article->cover_image_mobile)
        <section class="news-hero newsdetail-banner site-page-banner" style="--site-page-banner-height: {{ $bannerHeight }}px">
            <x-responsive-bg
                :pc="$article->cover_image"
                :mobile="$article->cover_image_mobile"
                class="news-hero-media newsdetail-banner-media"
                aria-hidden="true"
            />
            <div class="news-hero-container"></div>
        </section>
    @endif

    <article class="newsdetail-article">
        <div class="news-shell">
            <nav class="newsdetail-breadcrumb" aria-label="新闻资讯">
                <a href="{{ localized_route('home', [], $locale ?? null) }}">首页</a>
                <span>/</span>
                <a href="{{ route('news.index', request()->only('lang')) }}">新闻资讯</a>
                <span>/</span>
                <span aria-current="page">{{ $article->title }}</span>
            </nav>

            @if($article->category)
                <a href="{{ route('news.index', array_merge(request()->only('lang'), ['category' => $article->category->slug])) }}" class="news-badge news-badge--link">
                    <span class="news-badge-dot"></span>
                    <span>{{ $article->category->name }}</span>
                </a>
            @endif
            <div class="news-meta news-meta-detail">
                <img src="{{ asset('home-assets/home-news-calendar.svg') }}" alt="" aria-hidden="true">
                <span>{{ $article->displayDate() }}</span>
                @if($article->author)
                    <span class="newsdetail-author">{{ $article->author }}</span>
                @endif
            </div>
            <h1 class="newsdetail-title">{{ $article->title }}</h1>

            @if($article->content)
                <div class="newsdetail-content cms-rich-content">
                    {!! $article->content !!}
                </div>
            @elseif($article->summary)
                <div class="newsdetail-content cms-rich-content">
                    <p>{{ $article->summary }}</p>
                </div>
            @endif
        </div>
    </article>

    @if($relatedArticles->isNotEmpty())
        <section class="newsdetail-related">
            <div class="news-shell">
                <h2 class="newsdetail-related-title">相关资讯</h2>
                <div class="news-grid news-grid--related">
                    @foreach($relatedArticles as $related)
                        @include('news.partials.card', ['article' => $related, 'readMoreLabel' => $readMoreLabel])
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>
@endsection

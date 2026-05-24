@extends('layouts.home')

@section('body_class', 'case-studies-page')

@section('content')
@php
    $bannerPc = media_url($pageSettings->banner_image_pc);
    $bannerMobile = media_url($pageSettings->banner_image_mobile) ?: $bannerPc;
    $heroHeight = $pageSettings->banner_height ?: 420;
    $pageTitle = $pageSettings->meta_title ?: ($pageSettings->page_title.' - '.$siteName);
@endphp

@push('head')
    <title>{{ $pageTitle }}</title>
    @if($pageSettings->meta_description)
        <meta name="description" content="{{ $pageSettings->meta_description }}">
    @endif
    <link rel="stylesheet" href="{{ asset('css/case-studies.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/case-studies.js') }}" defer></script>
@endpush

<div class="cs-page">
    <section class="cs-hero" style="--cs-hero-height: {{ $heroHeight }}px" aria-label="{{ $pageSettings->page_title }}">
        @if($bannerPc)
            <div class="cs-hero-media"
                 aria-hidden="true"
                 data-banner-bg
                 data-banner-pc="{{ $bannerPc }}"
                 @if($bannerMobile) data-banner-mobile="{{ $bannerMobile }}" @endif
                 style="background-image:url('{{ $bannerPc }}')"></div>
        @else
            <div class="cs-hero-media cs-hero-media--fallback" aria-hidden="true"></div>
        @endif
        <div class="cs-hero-overlay" aria-hidden="true"></div>
        <div class="cs-hero-container">
            <div class="cs-hero-content">
                <h1>{{ $pageSettings->page_title }}</h1>
                @if($pageSettings->page_subtitle)
                    <p>{{ $pageSettings->page_subtitle }}</p>
                @endif
            </div>
        </div>
    </section>

    @if($featuredCases->isNotEmpty())
        <section class="cs-featured" data-cs-featured aria-label="精选案例">
            @foreach($featuredCases as $i => $item)
                <div class="cs-featured__shell cs-featured__slide {{ $i === 0 ? 'is-active' : '' }}" data-cs-featured-slide>
                    <div class="cs-featured__media">
                        <a href="{{ $item->url() }}">
                            <img src="{{ media_url($item->cover_image) }}" alt="{{ $item->title }}" loading="{{ $i === 0 ? 'eager' : 'lazy' }}">
                        </a>
                    </div>
                    <div>
                        <div class="cs-featured__bar">
                            <div>
                                <h2><a href="{{ $item->url() }}" style="color:inherit;text-decoration:none">{{ $item->title }}</a></h2>
                                @if($featuredCases->count() > 1)
                                    <div class="cs-featured__dots">
                                        @foreach($featuredCases as $j => $_)
                                            <button type="button" class="cs-featured__dot {{ $j === $i ? 'is-active' : '' }}" data-cs-featured-dot data-cs-index="{{ $j }}" aria-label="第 {{ $j + 1 }} 个案例"></button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            @if($featuredCases->count() > 1)
                                <div class="cs-featured__nav">
                                    <button type="button" data-cs-featured-prev aria-label="上一案例">‹</button>
                                    <button type="button" data-cs-featured-next aria-label="下一案例">›</button>
                                </div>
                            @endif
                        </div>
                        <div class="cs-featured__panel">
                            @if($item->listExcerpt())
                                <p>{{ $item->listExcerpt() }}</p>
                            @endif
                            <div class="cs-featured__meta">
                                @if($item->region)<span>{{ $item->region }}</span>@endif
                                @if($item->sceneLabel())<span>{{ $item->sceneLabel() }}</span>@endif
                            </div>
                            @if($item->tagList())
                                <div class="cs-featured__tags">
                                    @foreach($item->tagList() as $tag)
                                        <span>{{ $tag }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </section>
    @endif

    <section class="cs-list-wrap" aria-label="案例列表">
        <div class="cs-list-inner">
            <nav class="cs-filters" aria-label="案例分类">
                <a href="{{ route('cases.index', array_filter(['lang' => $locale !== 'zh-cn' ? $locale : null])) }}"
                   class="{{ $activeCategorySlug === 'all' ? 'is-active' : '' }}">全部</a>
                @foreach($categories as $cat)
                    <a href="{{ route('cases.index', array_filter(['category' => $cat->slug, 'lang' => $locale !== 'zh-cn' ? $locale : null])) }}"
                       class="{{ $activeCategorySlug === $cat->slug ? 'is-active' : '' }}">{{ $cat->name }}</a>
                @endforeach
            </nav>

            <div class="cs-grid">
                @forelse($cases as $case)
                    <a class="cs-card" href="{{ $case->url() }}">
                        <div class="cs-card__media">
                            <img src="{{ media_url($case->cover_image) }}" alt="{{ $case->title }}" loading="lazy">
                        </div>
                        <div class="cs-card__body">
                            <h3>{{ $case->title }}</h3>
                            <div class="cs-card__meta">
                                @if($case->region)
                                    <span>{{ $case->region }}</span>
                                @endif
                                @if($case->region && $case->sceneLabel())
                                    <span class="cs-divider" aria-hidden="true"></span>
                                @endif
                                @if($case->sceneLabel())
                                    <span>{{ $case->sceneLabel() }}</span>
                                @endif
                            </div>
                            @if($case->tagList())
                                <div class="cs-card__tags">
                                    @foreach($case->tagList() as $tag)
                                        <span>{{ $tag }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <img class="cs-card__arrow" src="{{ asset('home-assets/Icon.png') }}" alt="" aria-hidden="true">
                    </a>
                @empty
                    <p class="cs-empty">暂无该分类下的案例，请在后台添加或更换筛选条件。</p>
                @endforelse
            </div>

            @if($cases->hasPages())
                <div class="cs-pagination">
                    {{ $cases->links() }}
                </div>
            @endif
        </div>
    </section>
</div>
@endsection

@extends('layouts.home')

@section('body_class', 'industry-cases-page industry-cases-detail has-immersive-header')

@section('content')
@php
    $detail = $solution->detail();
    $heroSlides = $solution->heroSlides();
    $navSections = $solution->detailNavSections();
    $stats = $detail['stats'] ?? [];
    $statsTitle = trim((string) ($stats['title'] ?? '')) ?: ('赋能'.$solution->title.'行业效率升级');
    $coverage = $detail['coverage'] ?? [];
    $scenes = $detail['scenes'] ?? [];
    $heroSummary = $solution->heroDescription();
    $pageTitle = $solution->meta_title ?: ($solution->title.' - '.$siteName);
    $indexUrl = route('industry-cases.index', current_lang() !== 'zh-cn' ? ['lang' => current_lang()] : []);
@endphp

@push('head')
    <title>{{ $pageTitle }}</title>
    @if($solution->meta_description)
        <meta name="description" content="{{ $solution->meta_description }}">
    @elseif($heroSummary)
        <meta name="description" content="{{ Str::limit($heroSummary, 160) }}">
    @endif
    <link rel="stylesheet" href="{{ versioned_asset('css/industry-cases.css') }}">
@endpush

@push('scripts')
    <script src="{{ versioned_asset('js/industry-cases-detail.js') }}" defer></script>
@endpush

<div class="ic-detail">
    {{-- Hero：3/4 手机、16/9 桌面，文案桌面垂直居中 --}}
    <section class="ic-detail-hero" aria-label="{{ $solution->title }}">
        <div class="ic-detail-hero__media-wrap">
            @if(count($heroSlides) > 1)
                <div class="ic-detail-hero__carousel" data-ic-hero-carousel>
                    <div class="ic-detail-hero__track">
                        @foreach($heroSlides as $i => $slide)
                            <div class="ic-detail-hero__slide{{ $i === 0 ? ' is-active' : '' }}" data-ic-hero-slide>
                                @if(filled($slide['video_url'] ?? null))
                                    <video class="ic-detail-hero__media" autoplay muted loop playsinline preload="metadata">
                                        <source src="{{ $slide['video_url'] }}" type="video/mp4">
                                    </video>
                                @elseif(filled($slide['image_pc'] ?? null))
                                    <x-responsive-bg
                                        :pc="$slide['image_pc']"
                                        :mobile="$slide['image_mobile']"
                                        class="ic-detail-hero__bg"
                                    />
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <div class="ic-detail-hero__dots" data-ic-hero-dots aria-hidden="true">
                        @foreach($heroSlides as $i => $slide)
                            <button type="button" class="{{ $i === 0 ? 'is-active' : '' }}" data-ic-hero-dot aria-label="第 {{ $i + 1 }} 张"></button>
                        @endforeach
                    </div>
                </div>
            @elseif(count($heroSlides) === 1)
                @php $slide = $heroSlides[0]; @endphp
                @if(filled($slide['video_url'] ?? null))
                    <video class="ic-detail-hero__media" autoplay muted loop playsinline preload="metadata">
                        <source src="{{ $slide['video_url'] }}" type="video/mp4">
                    </video>
                @elseif(filled($slide['image_pc'] ?? null))
                    <x-responsive-bg
                        :pc="$slide['image_pc']"
                        :mobile="$slide['image_mobile']"
                        class="ic-detail-hero__bg"
                    />
                @else
                    <div class="ic-detail-hero__bg ic-detail-hero__bg--fallback" aria-hidden="true"></div>
                @endif
            @elseif($solution->cover_image)
                <x-responsive-bg
                    :pc="$solution->cover_image"
                    :mobile="$solution->cover_image_mobile"
                    class="ic-detail-hero__bg"
                />
            @else
                <div class="ic-detail-hero__bg ic-detail-hero__bg--fallback" aria-hidden="true"></div>
            @endif
            <div class="ic-detail-hero__overlay" aria-hidden="true"></div>
        </div>
        <div class="ic-detail-hero__content">
            <div class="ic-detail-container ic-detail-hero__copy">
                <h1>{{ $solution->title }}</h1>
                @if($heroSummary)
                    <p>{{ $heroSummary }}</p>
                @endif
            </div>
        </div>
    </section>

    {{-- 桌面端二级导航：紧跟首屏 Banner，上滑后吸顶至主菜单下方 --}}
    @if(count($navSections) > 1)
        <nav class="ic-subnav" id="icSubnav" aria-label="{{ $solution->title }} 页面导航">
            <div class="ic-subnav__bar">
                <div class="ic-detail-container ic-subnav__inner">
                    <h2 class="ic-subnav__title">{{ $solution->title }}</h2>
                    <ul class="ic-subnav__links" data-ic-detail-nav>
                        @foreach($navSections as $section)
                            <li>
                                <a href="#{{ $section['id'] }}" data-ic-detail-link>{{ $section['label'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </nav>
    @endif

    {{-- 解决方案价值 --}}
    @if(!empty($stats['items']))
        <section class="ic-band ic-band--gray" id="ic-section-stats" data-ic-detail-section>
            <div class="ic-detail-container">
                <h2 class="ic-band__title">{{ $statsTitle }}</h2>
                <div class="ic-stats-row">
                    @foreach($stats['items'] as $item)
                        <article class="ic-stat-card">
                            @if(filled($item['icon'] ?? null))
                                <div class="ic-stat-card__icon">
                                    <img src="{{ media_url($item['icon']) }}" alt="" loading="lazy" width="96" height="96">
                                </div>
                            @endif
                            <div class="ic-stat-card__text">
                                <h3>{{ $item['label'] ?? '' }}</h3>
                                <p>{{ $item['value'] ?? '' }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
                @if(filled($stats['footnote'] ?? null))
                    <p class="ic-stats-footnote">{{ $stats['footnote'] }}</p>
                @endif
            </div>
        </section>
    @endif

    {{-- 场景覆盖与作业流程（白底独立区块） --}}
    @if(filled($coverage['title'] ?? null) || filled($coverage['subtitle'] ?? null) || filled($coverage['image_pc'] ?? null))
        <section class="ic-band ic-band--white ic-band--coverage" id="ic-section-coverage" data-ic-detail-section>
            <div class="ic-detail-container">
                @if(filled($coverage['title'] ?? null))
                    <h2 class="ic-band__title ic-band__title--sm">{{ $coverage['title'] }}</h2>
                @endif
                @if(filled($coverage['subtitle'] ?? null))
                    <p class="ic-coverage-desc">{{ $coverage['subtitle'] }}</p>
                @endif
                @if(filled($coverage['image_pc'] ?? null))
                    <div class="ic-coverage-visual">
                        <x-responsive-image
                            :pc="$coverage['image_pc']"
                            :mobile="$coverage['image_mobile'] ?? null"
                            :alt="$coverage['title'] ?? $solution->title"
                            loading="lazy"
                        />
                    </div>
                @endif
            </div>
        </section>
    @endif

    {{-- 核心应用优势：每个场景独立灰底模块 --}}
    @if(!empty($scenes))
        <div id="ic-section-scenes" data-ic-detail-section>
            @foreach($scenes as $sceneIndex => $scene)
                @php
                    $slides = is_array($scene['slides'] ?? null) ? $scene['slides'] : [];
                    $products = is_array($scene['products'] ?? null) ? $scene['products'] : [];
                    $hasCarousel = $slides !== [];
                    $hasCards = filled($scene['challenge'] ?? null) || filled($scene['advantage'] ?? null);
                    $hasProducts = $products !== [];
                @endphp
                <section class="ic-band ic-band--gray ic-scene-band">
                    <div class="ic-detail-container">
                        <article class="ic-scene-article">
                            @if(filled($scene['title'] ?? null))
                                <h2 class="ic-scene-article__title">{{ $scene['title'] }}</h2>
                            @endif

                            @if($hasCarousel || $hasCards)
                                <div class="ic-scene-article__row{{ ($hasCarousel && $hasCards) ? ' ic-scene-article__row--split' : '' }}" data-ic-scene-row>
                                    @if($hasCarousel)
                                        <div class="ic-scene-article__media">
                                            <div class="ic-scene-carousel" data-ic-scene-carousel id="ic-scene-{{ $sceneIndex }}">
                                                <div class="ic-scene-carousel__viewport">
                                                    @foreach($slides as $si => $slide)
                                                        <div class="ic-scene-carousel__slide{{ $si === 0 ? ' is-active' : '' }}" data-ic-scene-slide>
                                                            @if(filled($slide['image'] ?? null))
                                                                <img src="{{ media_url($slide['image']) }}" alt="{{ $slide['label'] ?? '' }}" loading="{{ $si === 0 ? 'eager' : 'lazy' }}">
                                                            @endif
                                                            @if(filled($slide['label'] ?? null))
                                                                <span class="ic-scene-carousel__label">{{ $slide['label'] }}</span>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                                @if(count($slides) > 1)
                                                    <div class="ic-scene-carousel__nav">
                                                        <button type="button" class="ic-scene-carousel__arrow ic-scene-carousel__arrow--prev" data-ic-scene-prev aria-label="上一张"></button>
                                                        <button type="button" class="ic-scene-carousel__arrow ic-scene-carousel__arrow--next" data-ic-scene-next aria-label="下一张"></button>
                                                    </div>
                                                    <div class="ic-scene-carousel__pagination" data-ic-scene-dots>
                                                        @foreach($slides as $si => $slide)
                                                            <button type="button" class="{{ $si === 0 ? 'is-active' : '' }}" data-ic-scene-dot aria-label="第 {{ $si + 1 }} 张"></button>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    @if($hasCards)
                                        <div class="ic-scene-article__cards{{ $hasCarousel ? '' : ' ic-scene-article__cards--solo' }}">
                                            @if(filled($scene['challenge'] ?? null))
                                                <section class="ic-scene-card ic-scene-card--challenge">
                                                    <h3>场景挑战</h3>
                                                    <div class="ic-scene-card__body">{!! nl2br(e($scene['challenge'])) !!}</div>
                                                </section>
                                            @endif
                                            @if(filled($scene['advantage'] ?? null))
                                                <section class="ic-scene-card ic-scene-card--advantage">
                                                    <h3>方案优势</h3>
                                                    <div class="ic-scene-card__body">{!! nl2br(e($scene['advantage'])) !!}</div>
                                                </section>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @if($hasProducts)
                                <div class="ic-scene-article__products">
                                    <h3 class="ic-scene-article__products-title">机器人解决方案</h3>
                                    <div class="ic-scene-article__products-grid">
                                        @foreach($products as $product)
                                            @php
                                                $linkUrl = $solution->productLinkUrl($product['url'] ?? null);
                                                $linkText = trim((string) ($product['link_text'] ?? '')) ?: '了解更多';
                                            @endphp
                                            <div class="ic-product-row">
                                                @if(filled($product['image'] ?? null))
                                                    <div class="ic-product-row__media">
                                                        <img src="{{ media_url($product['image']) }}" alt="{{ $product['title'] ?? '' }}" loading="lazy">
                                                    </div>
                                                @endif
                                                <div class="ic-product-row__body">
                                                    @if(filled($product['title'] ?? null))
                                                        <h4>{{ $product['title'] }}</h4>
                                                    @endif
                                                    @if(filled($product['bullets'] ?? null))
                                                        <p class="ic-product-row__bullets">{!! nl2br(e($product['bullets'])) !!}</p>
                                                    @endif
                                                    @if(filled($product['url'] ?? null))
                                                        <a class="ic-product-row__link" href="{{ $linkUrl }}">{{ $linkText }}<svg width="7" height="12" viewBox="0 0 7 12" fill="none" aria-hidden="true"><path d="M1 1L6 6L1 11" stroke="currentColor"/></svg></a>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </article>
                    </div>
                </section>
            @endforeach
        </div>
    @endif

    @if(filled($solution->content))
        <section class="ic-band ic-band--white">
            <div class="ic-detail-container ic-detail-body">
                <div class="prose">{!! $solution->content !!}</div>
            </div>
        </section>
    @endif

    @if($otherSolutions->isNotEmpty())
        <section class="ic-band ic-band--gray ic-band--related" id="ic-section-related" data-ic-detail-section aria-label="更多解决方案">
            <div class="ic-detail-container">
                <div class="ic-related-head">
                    <h2 class="ic-band__title ic-band__title--inline">更多解决方案</h2>
                    <a class="ic-related-more" href="{{ $indexUrl }}">了解更多<svg width="7" height="12" viewBox="0 0 7 12" fill="none" aria-hidden="true"><path d="M1 1L6 6L1 11" stroke="currentColor"/></svg></a>
                </div>
                <div class="ic-related-carousel" data-ic-related-carousel>
                    <div class="ic-related-carousel__track" data-ic-related-track>
                        @foreach($otherSolutions as $other)
                            <a class="ic-related-card" href="{{ $other->url() }}">
                                @if($other->cover_image)
                                    <x-responsive-image
                                        :pc="$other->cover_image"
                                        :mobile="$other->cover_image_mobile"
                                        :alt="$other->title"
                                        class="ic-related-card__img"
                                        loading="lazy"
                                    />
                                @else
                                    <div class="ic-related-card__img ic-related-card__img--placeholder"></div>
                                @endif
                                <div class="ic-related-card__body">
                                    <h3>{{ $other->title }}</h3>
                                    @if($other->cardSummary())
                                        <p>{{ $other->cardSummary() }}</p>
                                    @endif
                                    <span class="ic-related-card__arrow" aria-hidden="true"></span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <div class="ic-related-carousel__controls">
                        <button type="button" class="ic-related-carousel__btn ic-related-carousel__btn--prev" data-ic-related-prev aria-label="向左"></button>
                        <button type="button" class="ic-related-carousel__btn ic-related-carousel__btn--next" data-ic-related-next aria-label="向右"></button>
                    </div>
                </div>
            </div>
        </section>
    @endif
</div>
@endsection

@extends('layouts.home')

@section('body_class', 'productdetail-page news-page')

@php
    $labels = $detailLabels;
    $heroImage = media_url($product->heroImage());
    $heroVideo = media_url($product->hero_video);
    $categoryLabel = $product->categoryPillLabel();
    $gallery = $product->detailGalleryList();
    $features = $product->detailFeatureList();
    $detailHeroPc = $product->detail_hero_image;
    $detailHeroMobile = $product->detail_hero_image_mobile;
    $contactBgStyle = responsive_bg_style($product->contact_bg_image, $product->contact_bg_image_mobile);
    $rightsTitle = $rights['title'] ?? null;
    $rightsTime = $rights['time_range'] ?? null;
    $rightsNotice = $rights['notice'] ?? null;
    $rightsHighlights = $rights['highlights'] ?? [];
    $rightsListTitle = $rights['list_title'] ?? null;
    $rightsListItems = $rights['list_items'] ?? [];
    $pageTitle = $product->meta_title ?: ($product->name.' - '.($pageSettings->meta_title ?: '产品详情'));
    $metaDescription = $product->meta_description ?: $product->subtitle;
@endphp

@push('head')
    <title>{{ $pageTitle }}</title>
    @if($metaDescription)
        <meta name="description" content="{{ $metaDescription }}">
    @endif
    <link rel="stylesheet" href="{{ versioned_asset('css/product-banner.css') }}">
    <link rel="stylesheet" href="{{ versioned_asset('css/product-detail.css') }}">
@endpush

@push('scripts')
    <script id="productDetailData" type="application/json">@json($detailPageJson)</script>
    <script src="{{ versioned_asset('js/product-banner.js') }}" defer></script>
    <script src="{{ versioned_asset('js/product-detail.js') }}" defer></script>
@endpush

@section('content')
<div class="productdetail-main new_xz">
    <section class="product-hero product-hero--with-copy">
        @include('products.partials.banner', [
            'imagePc' => $heroImage,
            'videoUrl' => $heroVideo,
            'poster' => $heroImage,
            'alt' => $product->name,
            'fetchpriority' => true,
            'videoId' => 'productDetailBannerVideo',
        ])
        <div class="product-hero-grid">
            <div class="product-hero-copy">
                @if($categoryLabel)
                    <div class="product-hero-kicker">
                        <span class="product-hero-pill product-hero-pill-accent">{{ $categoryLabel }}</span>
                    </div>
                @endif
                <h1>{{ $product->name }}</h1>
                @if($product->subtitle)
                    <p class="product-hero-desc">{{ $product->subtitle }}</p>
                @endif
                <div class="product-hero-actions">
                    <a href="#contactForm" class="product-button product-button-primary">{{ $labels['contact_now'] }}</a>
                    @if($specGroups !== [])
                        <a href="#productSpecs" class="product-button product-button-secondary">{{ $labels['view_specs'] }}</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="product-mt" aria-hidden="true"></div>
    </section>

    <section class="product-breadcrumb-shell">
        <div class="product-shell">
            <nav class="product-breadcrumb" aria-label="产品中心">
                <a href="{{ localized_route('home', [], $locale ?? null) }}">{{ $labels['breadcrumb_home'] }}</a>
                <span>/</span>
                <a href="{{ route('products.index', request()->only('lang')) }}">{{ $labels['breadcrumb_list'] }}</a>
                <span>/</span>
                <span aria-current="page">{{ $product->name }}</span>
            </nav>
        </div>
    </section>

    @if($showcaseSlides !== [])
        <section class="product-showcase">
            <div class="product-shell product-showcase-shell">
                <div class="showcase-carousel" aria-label="产品展示">
                    <div class="showcase-stage">
                        <div class="showcase-stage-media">
                            <a id="showcaseMainLink" class="showcase-stage-link is-disabled" href="javascript:void(0);" aria-disabled="true" tabindex="-1">
                                <img id="showcaseMainImage" src="{{ $showcaseSlides[0]['image'] }}" alt="{{ $showcaseSlides[0]['alt'] }}" fetchpriority="high" decoding="async">
                            </a>
                            @if(count($showcaseSlides) > 1)
                                <button type="button" class="showcase-arrow showcase-arrow-prev" id="showcasePrev" aria-label="上一张">
                                    <img src="{{ asset('home-assets/home-hero-prev.svg') }}" alt="" aria-hidden="true">
                                </button>
                                <button type="button" class="showcase-arrow showcase-arrow-next" id="showcaseNext" aria-label="下一张">
                                    <img src="{{ asset('home-assets/home-hero-next.svg') }}" alt="" aria-hidden="true">
                                </button>
                            @endif
                        </div>
                    </div>
                    @if(count($showcaseSlides) > 1)
                        <div class="showcase-indicators" id="showcaseThumbs" aria-label="轮播切换">
                            @foreach($showcaseSlides as $i => $slide)
                                <button type="button" class="showcase-indicator {{ $i === 0 ? 'is-active' : '' }}" data-slide-index="{{ $i }}" aria-label="切换到第 {{ $i + 1 }} 张"></button>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    @if($detailHeroPc || $detailHeroMobile || $product->displayImage() || $product->summary || $gallery !== [] || $features !== [])
        <section class="product-details">
            <div class="product-shell">
                <div class="section-heading product-details-heading">
                    <div class="product-overline product-overline-center product-details-overline">
                        <span class="product-overline-line"></span>
                        <span>{{ $labels['details_overline'] }}</span>
                        <span class="product-overline-line"></span>
                    </div>
                    <h2>{{ $product->name }}</h2>
                    @if($product->category?->subtitle)
                        <p>{{ $product->category->subtitle }}</p>
                    @endif
                </div>

                @if($detailHeroPc || $detailHeroMobile || $product->displayImage())
                    <article class="details-hero-card">
                        <x-responsive-image
                            :pc="$detailHeroPc ?: $product->displayImage()"
                            :mobile="$detailHeroMobile"
                            :alt="$product->name"
                            loading="lazy"
                        />
                    </article>
                @endif

                @if($product->summary)
                    <div class="details-summary">
                        <span class="details-summary-line" aria-hidden="true"></span>
                        <p>{{ $product->summary }}</p>
                    </div>
                @endif

                @if($gallery !== [])
                    <div class="details-gallery">
                        @foreach($gallery as $i => $image)
                            <article class="details-gallery-card">
                                <img src="{{ media_url($image) }}" alt="{{ $product->name }} {{ $i + 1 }}" loading="lazy" decoding="async">
                            </article>
                        @endforeach
                    </div>
                @endif

                @if($features !== [])
                    <div class="detail-feature-grid">
                        @foreach($features as $text)
                            <article class="detail-feature-card">
                                <span class="detail-feature-icon" aria-hidden="true">
                                    <svg viewBox="0 0 20 20" width="20" height="20" fill="none"><circle cx="10" cy="10" r="9" stroke="currentColor" stroke-width="1.5"/><path d="M6 10.2l2.4 2.4L14 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </span>
                                <p>{{ $text }}</p>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    @endif

    @if($specGroups !== [])
        <section class="product-specs" id="productSpecs">
            <div class="product-shell">
                <div class="section-heading section-heading-left product-specs-heading">
                    <div class="product-overline">
                        <span class="product-overline-line"></span>
                        <span>{{ $labels['specs_overline'] }}</span>
                    </div>
                    <h2>{{ $labels['specs_title'] }}</h2>
                </div>

                @if(count($specGroups) > 1)
                    <div class="specs-tabs" id="specTabs" role="tablist" aria-label="{{ $labels['specs_title'] }}">
                        @foreach($specGroups as $i => $group)
                            <button type="button"
                                    class="specs-tab {{ $i === 0 ? 'is-active' : '' }}"
                                    data-spec-key="{{ $group['key'] }}"
                                    role="tab"
                                    aria-selected="{{ $i === 0 ? 'true' : 'false' }}">{{ $group['label'] }}</button>
                        @endforeach
                    </div>
                @endif

                <div class="specs-table-card">
                    <div class="specs-table-head">
                        <span>{{ $labels['specs_item'] }}</span>
                        <span>{{ $labels['specs_value'] }}</span>
                    </div>
                    <div class="specs-table-body" id="specTableBody">
                        @foreach($specGroups[0]['rows'] ?? [] as $row)
                            <dl class="specs-table-row">
                                <dt>{{ $row['label'] }}</dt>
                                <dd>{{ $row['value'] }}</dd>
                            </dl>
                        @endforeach
                    </div>
                    <div class="specs-table-foot">
                        <p>{{ $labels['specs_notice'] }}</p>
                        @if($product->specDocumentUrl())
                            <a href="{{ $product->specDocumentUrl() }}" class="spec-download-btn" id="downloadSpecBtn" target="_blank" rel="noopener">
                                <span>{{ $labels['download_specs'] }}</span>
                            </a>
                        @else
                            <button type="button" class="spec-download-btn" id="downloadSpecBtn" disabled>
                                <span>{{ $labels['download_missing'] }}</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if($rightsTitle || $rightsHighlights !== [] || $rightsListItems !== [])
        <section class="product-rights">
            <div class="product-shell">
                <div class="product-rights-inner">
                    @if($rightsTitle)
                        <h2 class="product-rights-title">{{ $rightsTitle }}</h2>
                    @endif
                    @if($rightsTime)
                        <p class="product-rights-time">{{ $rightsTime }}</p>
                    @endif
                    @if($rightsNotice)
                        <div class="product-rights-notice"><p>{{ $rightsNotice }}</p></div>
                    @endif
                    <div class="product-rights-content cms-rich-content">
                        <div class="product-rights-groups">
                            @foreach($rightsHighlights as $item)
                                @if(filled($item['html'] ?? $item['text'] ?? null))
                                    <section class="product-rights-group product-rights-group-block">
                                        <div class="product-rights-item product-rights-item-block">
                                            <div class="product-rights-copy">
                                                <span class="product-rights-label">{{ $item['text'] ?? '' }}</span>
                                            </div>
                                            <span class="product-rights-check" aria-hidden="true"></span>
                                        </div>
                                    </section>
                                @endif
                            @endforeach
                            @if($rightsListItems !== [])
                                <section class="product-rights-group product-rights-group-list">
                                    @if($rightsListTitle)
                                        <h3 class="product-right-shouh">{{ $rightsListTitle }}</h3>
                                    @endif
                                    <ul class="product-rights-list">
                                        @foreach($rightsListItems as $item)
                                            <li class="product-rights-item">
                                                <div class="product-rights-copy">
                                                    <span class="product-rights-label">{{ $item['text'] ?? '' }}</span>
                                                </div>
                                                <span class="product-rights-check" aria-hidden="true"></span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </section>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section class="product-contact">
        @if($contactBgStyle)
            <div class="product-contact-media has-responsive-bg" style="{{ $contactBgStyle }}" aria-hidden="true"></div>
        @endif
        <div class="product-contact-shell">
            <div class="product-contact-copy">
                <div class="product-overline">
                    <span class="product-overline-line"></span>
                    <span>{{ $labels['contact_overline'] }}</span>
                </div>
                <h2>{{ $siteName }}</h2>
                <p>{{ $labels['contact_desc'] }}</p>
            </div>

            <form class="product-contact-card" id="contactForm">
                @csrf
                <div class="product-contact-card-head">
                    <h3>{{ $labels['form_title'] }}</h3>
                </div>
                <input type="hidden" name="product" value="{{ $product->name }}">
                <div class="product-form-grid">
                    <label class="product-form-row">
                        <span>您的姓名</span>
                        <input type="text" name="name" placeholder="请输入姓名" required>
                    </label>
                    <label class="product-form-row">
                        <span>联系电话</span>
                        <input type="tel" name="phone" placeholder="请输入手机号码" required>
                    </label>
                    <label class="product-form-row">
                        <span>电子邮箱</span>
                        <input type="email" name="email" placeholder="请输入邮箱地址">
                    </label>
                    <label class="product-form-row">
                        <span>所在城市</span>
                        <input type="text" name="city" placeholder="请输入所在城市">
                    </label>
                    <label class="product-form-row product-form-row-select">
                        <span>咨询主题</span>
                        <select name="topic">
                            <option value="">请选择咨询主题</option>
                            <option value="purchase">购车咨询</option>
                            <option value="custom">定制方案</option>
                            <option value="service">售后服务</option>
                        </select>
                    </label>
                    <label class="product-form-row product-form-row-area">
                        <span>留言内容</span>
                        <textarea name="remark" rows="4" placeholder="请描述您的需求或问题..."></textarea>
                    </label>
                </div>
                <p class="product-form-status" id="productFormStatus" hidden></p>
                <button type="submit" class="product-button product-button-primary product-form-submit">{{ $labels['form_submit'] }}</button>
            </form>
        </div>
    </section>
</div>
@endsection

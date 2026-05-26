@extends('layouts.home')

@section('body_class', 'company-about-page')

@section('content')
@php
    $pageTitle = $settings->meta_title ?: ('关于我们 - '.$siteName);
    $heroUrl = media_url($settings->hero_media_url, asset('home-assets/69e9ff102a425.jpg'));
    $heroUrlMobile = media_url($settings->hero_media_mobile) ?: $heroUrl;
    $heroPoster = media_url($settings->hero_poster_url, $heroUrl);
    $heroPosterMobile = media_url($settings->hero_poster_mobile) ?: $heroPoster;
    $isVideo = $settings->hero_media_type === 'video' && filled($settings->hero_media_url);
    $metrics = $settings->global_metrics;
    $stations = $settings->normalizedServiceStations();
    $introParagraphs = array_filter(array_map('trim', preg_split("/\r\n|\n|\r/", (string) $settings->intro_body)));
    $introImagePc = $settings->intro_side_image ?: $settings->hero_media_url;
    $introVisualBg = responsive_bg_style($introImagePc, $settings->intro_side_image_mobile, $settings->hero_media_url);
@endphp

@push('head')
    <title>{{ $pageTitle }}</title>
    @if($settings->meta_description)
        <meta name="description" content="{{ $settings->meta_description }}">
    @endif
    <link rel="stylesheet" href="{{ asset('css/company-about.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/company-about.js') }}" defer></script>
@endpush

<div class="about-page">
    {{-- 顶部 Banner（全宽原图/原视频，无遮罩） --}}
    @if($settings->showsBanner())
    <section class="about-banner" aria-label="关于我们">
        @if($isVideo)
            <video class="about-banner__media"
                   autoplay muted loop playsinline
                   poster="{{ $heroPoster }}"
                   data-banner-poster-pc="{{ $heroPoster }}"
                   data-banner-poster-mobile="{{ $heroPosterMobile }}">
                <source src="{{ $heroUrl }}" type="video/mp4"
                        data-banner-video-pc="{{ $heroUrl }}"
                        data-banner-video-mobile="{{ $heroUrlMobile }}">
            </video>
        @else
            <x-responsive-image
                :pc="$settings->hero_media_url"
                :mobile="$settings->hero_media_mobile"
                fallback="home-assets/69e9ff102a425.jpg"
                :alt="$settings->intro_title ?: '关于我们'"
                class="about-banner__media"
                fetchpriority="high"
            />
        @endif
    </section>
    @endif

    {{-- 公司简介（上移 10px 与 Banner 衔接） --}}
    @if($settings->showsIntro())
    <section class="about-section about-section--profile">
        <div class="site-shell">
            @include('company.partials.section-title', [
                'eyebrow' => $settings->intro_eyebrow,
                'title' => $settings->intro_title ?: '关于众鼠',
            ])
            <div class="about-intro-grid">
                <div class="about-intro-card">
                    @forelse($introParagraphs as $paragraph)
                        <p>{{ $paragraph }}</p>
                    @empty
                        @if($settings->meta_description)
                            <p>{{ $settings->meta_description }}</p>
                        @endif
                    @endforelse
                    @if(count($metrics))
                        <div class="about-stat-grid">
                            @foreach($metrics as $stat)
                                <div class="about-stat">
                                    <strong>{{ $stat['value'] ?? '' }}</strong>
                                    <span>{{ $stat['label'] ?? '' }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="about-intro-visual @if($introVisualBg) has-responsive-bg @endif"
                     @if($introVisualBg) style="{{ $introVisualBg }}" @else style="--about-intro-image: url('{{ media_url($introImagePc, asset('home-assets/69e9ff102a425.jpg')) }}')" @endif>
                    @if($settings->intro_visual_title || $settings->intro_visual_text)
                        <div class="about-intro-visual__text">
                            @if($settings->intro_visual_title)
                                <strong>{{ $settings->intro_visual_title }}</strong>
                            @endif
                            @if($settings->intro_visual_text)
                                <span>{{ $settings->intro_visual_text }}</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- 全球化布局（公司简介下方，滚动逐项动效） --}}
    @if($settings->showsGlobalLayout())
        @include('company.partials.global-layout', ['settings' => $settings])
    @endif

    {{-- 核心能力 --}}
    @if($settings->showsCapabilities() && count($capabilities))
        <section class="about-section about-section--gray">
            <div class="site-shell">
                @include('company.partials.section-title', [
                    'eyebrow' => $settings->capabilities_eyebrow,
                    'title' => $settings->capabilities_title,
                    'lead' => $settings->capabilities_lead,
                ])
                <div class="about-cap-grid">
                    @foreach($capabilities as $item)
                    <article class="about-cap-card">
                        <div class="about-cap-card__icon" aria-hidden="true">{{ $item['icon'] }}</div>
                        <h3>{{ $item['title'] }}</h3>
                        <p>{{ $item['text'] }}</p>
                    </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- 服务站 --}}
    @if($settings->showsServiceStations() && count($stations))
        <section class="about-section">
            <div class="site-shell">
                @include('company.partials.section-title', [
                    'eyebrow' => $settings->global_station_eyebrow,
                    'title' => $settings->global_station_heading,
                ])
                <div class="about-switch" data-cp-tabs>
                    <div class="about-switch__toolbar">
                        <div class="about-switch__tabs" role="tablist" aria-label="服务站">
                            @foreach($stations as $i => $station)
                                <button type="button"
                                        class="about-switch__tab {{ $i === 0 ? 'is-active' : '' }}"
                                        data-cp-tab
                                        role="tab"
                                        aria-selected="{{ $i === 0 ? 'true' : 'false' }}">{{ $station['tab_label'] ?? '' }}</button>
                            @endforeach
                        </div>
                    </div>
                    <div class="about-switch__panels">
                        @foreach($stations as $i => $station)
                            @php
                                $stationImage = media_url($station['image'] ?? null, asset('home-assets/69e9ff102a425.jpg'));
                            @endphp
                            <article class="about-switch__panel {{ $i === 0 ? 'is-active' : '' }}"
                                     data-cp-panel
                                     role="tabpanel"
                                     @if($i > 0) hidden @endif>
                                <div class="about-stations__card">
                                    <div class="about-stations__visual">
                                        <img src="{{ $stationImage }}" alt="{{ $station['title'] ?? $station['tab_label'] ?? '' }}" loading="lazy">
                                    </div>
                                    <div class="about-stations__body">
                                        @if(! empty($station['badge']))
                                            <span class="about-stations__badge">{{ $station['badge'] }}</span>
                                        @endif
                                        @if(! empty($station['title']))
                                            <h3>{{ $station['title'] }}</h3>
                                        @endif
                                        @if(! empty($station['description']))
                                            <p>{{ $station['description'] }}</p>
                                        @endif
                                        @if(! empty($station['phone']))
                                            <p class="about-stations__phone">{{ $station['phone'] }}</p>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- 发展路径 --}}
    @if($settings->showsTimeline() && $pathSteps->isNotEmpty())
        <section class="about-section about-section--dark">
            <div class="site-shell">
                @include('company.partials.section-title', [
                    'eyebrow' => $settings->timeline_eyebrow,
                    'title' => $settings->timeline_title,
                    'lead' => $settings->timeline_lead,
                    'theme' => 'dark',
                ])
                <div class="about-path">
                    @foreach($pathSteps as $index => $step)
                        <div class="about-path__item">
                            <div class="about-path__index">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</div>
                            <div>
                                <h3>{{ $step->title }}</h3>
                                <p>
                                    @if($step->month_label)
                                        {{ $step->month_label }} ·
                                    @endif
                                    {{ $step->year }} 年重要进展
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- 企业文化（Tab 切换，布局同服务站） --}}
    @if($settings->showsCulture() && $cultureValues->isNotEmpty())
        <section class="about-section about-section--gray about-culture" id="culture">
            <div class="site-shell">
                @include('company.partials.section-title', [
                    'eyebrow' => $settings->culture_eyebrow,
                    'title' => $settings->culture_title ?: '企业文化',
                    'lead' => $settings->culture_mission_text,
                ])
                <div class="about-switch" data-cp-tabs>
                    <div class="about-switch__toolbar">
                        <div class="about-switch__tabs" role="tablist" aria-label="企业文化">
                            @foreach($cultureValues as $i => $value)
                                <button type="button"
                                        class="about-switch__tab {{ $i === 0 ? 'is-active' : '' }}"
                                        data-cp-tab
                                        role="tab"
                                        aria-selected="{{ $i === 0 ? 'true' : 'false' }}">{{ $value->label }}</button>
                            @endforeach
                        </div>
                    </div>
                    <div class="about-switch__panels">
                        @foreach($cultureValues as $i => $value)
                            <article class="about-switch__panel {{ $i === 0 ? 'is-active' : '' }}"
                                     data-cp-panel
                                     role="tabpanel"
                                     @if($i > 0) hidden @endif>
                                <div class="about-culture__card">
                                    <div class="about-culture__visual">
                                        @if($value->icon)
                                            <img src="{{ media_url($value->icon) }}" alt="{{ $value->label }}" loading="lazy">
                                        @else
                                            <span class="about-culture__glyph" aria-hidden="true">{{ $value->label }}</span>
                                        @endif
                                    </div>
                                    <div class="about-culture__body">
                                        <span class="about-culture__badge">{{ $value->label }}</span>
                                        @if($value->subtitle)
                                            <h3 class="about-culture__name">{{ $value->subtitle }}</h3>
                                        @endif
                                        @if($value->essence)
                                            <div class="about-culture__block">
                                                <p class="about-culture__tag">核心内涵</p>
                                                <p class="about-culture__text">{{ $value->essence }}</p>
                                            </div>
                                        @endif
                                        @if($value->practice)
                                            <div class="about-culture__block about-culture__block--practice">
                                                <p class="about-culture__tag">科技实践</p>
                                                <p class="about-culture__text">{{ $value->practice }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- 品牌荣誉（资质 / 奖牌 / 证书图片墙） --}}
    @if($settings->showsHonors() && $honors->isNotEmpty())
        <section class="about-section about-section--gray about-honors-section">
            <div class="site-shell">
                @include('company.partials.section-title', [
                    'eyebrow' => $settings->honors_eyebrow,
                    'title' => $settings->honors_title ?: '品牌荣誉',
                    'lead' => $settings->honors_subtitle,
                ])
                <ul class="about-honors-gallery">
                    @foreach($honors as $honor)
                        <li class="about-honor-card">
                            <div class="about-honor-card__frame">
                                @if($honor->image)
                                    <img src="{{ media_url($honor->image) }}"
                                         alt="{{ $honor->title }}"
                                         loading="lazy"
                                         decoding="async">
                                @else
                                    <div class="about-honor-card__empty" aria-hidden="true">
                                        <span>待上传图片</span>
                                    </div>
                                @endif
                            </div>
                            <div class="about-honor-card__meta">
                                @if($honor->category)
                                    <span class="about-honor-card__tag">{{ $honor->categoryLabel() }}</span>
                                @endif
                                <p class="about-honor-card__title">{{ $honor->title }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    {{-- 团队介绍 --}}
    @if($settings->showsTeam() && ($teamFeatured || $teamMembers->isNotEmpty()))
        <section class="about-section">
            <div class="site-shell">
                @include('company.partials.section-title', [
                    'eyebrow' => $settings->team_eyebrow,
                    'title' => $settings->team_title ?: '团队介绍',
                ])

                @if($teamFeatured)
                    <article class="about-team-lead">
                        <div class="about-team-lead__photo">
                            @if($teamFeatured->photo)
                                <img src="{{ media_url($teamFeatured->photo) }}" alt="{{ $teamFeatured->name }}" loading="lazy">
                            @else
                                <span class="about-team-lead__placeholder" aria-hidden="true">{{ mb_substr($teamFeatured->name, 0, 1) }}</span>
                            @endif
                        </div>
                        <div class="about-team-lead__body">
                            <p class="about-team-lead__badge">核心成员</p>
                            <h3 class="about-team-lead__name">{{ $teamFeatured->name }}</h3>
                            @if($teamFeatured->role)
                                <p class="about-team-lead__role">{{ $teamFeatured->role }}</p>
                            @endif
                            @if($teamFeatured->bio)
                                <p class="about-team-lead__bio">{{ $teamFeatured->bio }}</p>
                            @endif
                        </div>
                    </article>
                @endif

                @if($teamMembers->isNotEmpty())
                    <div class="about-team-tech">
                        <h3 class="about-team-tech__heading">{{ $settings->team_tech_subtitle ?: '我们技术团队人员介绍' }}</h3>
                        <ul class="about-team-grid">
                            @foreach($teamMembers as $member)
                                <li class="about-team-card">
                                    <div class="about-team-card__photo">
                                        @if($member->photo)
                                            <img src="{{ media_url($member->photo) }}" alt="{{ $member->name }}" loading="lazy">
                                        @else
                                            <span class="about-team-card__placeholder" aria-hidden="true">{{ mb_substr($member->name, 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <h4 class="about-team-card__name">{{ $member->name }}</h4>
                                    @if($member->role)
                                        <p class="about-team-card__role">{{ $member->role }}</p>
                                    @endif
                                    @if($member->bio)
                                        <p class="about-team-card__bio">{{ $member->bio }}</p>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </section>
    @endif
</div>
@endsection

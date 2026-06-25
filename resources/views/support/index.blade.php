@extends('layouts.home')

@section('body_class', 'support-page')

@php
    $heroPc = media_url($settings->hero_image_pc);
    $heroMobile = media_url($settings->hero_image_mobile) ?: $heroPc;
    $heroHeight = $settings->hero_height ?: 640;
    $pageTitle = $settings->meta_title ?: ('技术支持 - '.$siteName);
    $heroTitle = $settings->hero_title ?: '技术支持中心';
    $heroSubtitle = $settings->hero_subtitle;
    $heroEyebrow = $settings->hero_eyebrow;
@endphp

@push('head')
    <title>{{ $pageTitle }}</title>
    @if($settings->meta_description)
        <meta name="description" content="{{ $settings->meta_description }}">
    @endif
    @if($settings->meta_keywords)
        <meta name="keywords" content="{{ $settings->meta_keywords }}">
    @endif
    <link rel="stylesheet" href="{{ versioned_asset('css/support.css') }}">
@endpush

@push('scripts')
    <script id="supportPageData" type="application/json">@json($pageData)</script>
    <script>
        window.supportConfig = {
            regionUrl: @json(localized_route('support.region', [], $locale ?? null)),
            submitUrl: @json(localized_route('support.submit', [], $locale ?? null)),
            videoPlayUrl: @json(url('/support/videos')),
            csrfToken: @json(csrf_token()),
        };
    </script>
    <script src="{{ versioned_asset('js/support.js') }}" defer></script>
@endpush

@section('content')
<div class="support-main new_xz">
    <section class="support-hero site-page-banner" style="--site-page-banner-height: {{ $heroHeight }}px" aria-label="{{ $heroTitle }}">
        @if($heroPc || $settings->hero_image_pc)
            <div class="support-hero__media">
                <x-responsive-image
                    :pc="$settings->hero_image_pc"
                    :mobile="$settings->hero_image_mobile"
                    class="support-hero__img"
                    alt="{{ $heroTitle }}"
                    decoding="async"
                    :fetchpriority="'high'"
                />
            </div>
        @else
            <div class="support-hero__media support-hero__media--fallback" aria-hidden="true"></div>
        @endif
        <div class="support-hero__overlay" aria-hidden="true"></div>
        <div class="support-hero__shell">
            <div class="support-hero__content">
                @if($heroEyebrow)
                    <p class="support-hero__eyebrow">{{ $heroEyebrow }}</p>
                @endif
                <h1 class="support-hero__title">{{ $heroTitle }}</h1>
                @if($heroSubtitle)
                    <p class="support-hero__subtitle">{{ $heroSubtitle }}</p>
                @endif
                <nav class="support-hero__nav" aria-label="页面快捷导航">
                    <a class="support-hero__nav-link" href="#support-docs">技术文档</a>
                    <a class="support-hero__nav-link" href="#support-videos">视频教程</a>
                    <a class="support-hero__nav-link" href="#support-service">售后申请</a>
                </nav>
            </div>
            <dl class="support-hero__stats">
                <div class="support-hero__stat">
                    <dt>技术文档</dt>
                    <dd>{{ $docCount }}</dd>
                </div>
                <div class="support-hero__stat">
                    <dt>教学视频</dt>
                    <dd>{{ $videoCount }}</dd>
                </div>
                <div class="support-hero__stat">
                    <dt>服务响应</dt>
                    <dd>7×24</dd>
                </div>
            </dl>
        </div>
    </section>

    <section class="support-hub" aria-label="支持服务入口">
        <div class="support-hub__shell">
            <a class="support-hub__card" href="#support-docs">
                <span class="support-hub__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="28" height="28" fill="none"><path d="M7 4h7l5 5v11H7V4z" stroke="currentColor" stroke-width="1.6"/><path d="M14 4v5h5" stroke="currentColor" stroke-width="1.6"/></svg>
                </span>
                <strong>下载技术文档</strong>
                <span>产品手册、规格书、安装与运维 PDF 资料</span>
            </a>
            <a class="support-hub__card" href="#support-videos">
                <span class="support-hub__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="28" height="28" fill="none"><rect x="3" y="6" width="18" height="12" rx="2" stroke="currentColor" stroke-width="1.6"/><path d="M10 9.5v5l4.5-2.5L10 9.5z" fill="currentColor"/></svg>
                </span>
                <strong>观看视频教程</strong>
                <span>设备操作、调试流程与常见问题演示</span>
            </a>
            <a class="support-hub__card" href="#support-service">
                <span class="support-hub__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="28" height="28" fill="none"><path d="M4 14a8 8 0 0116 0v2H4v-2z" stroke="currentColor" stroke-width="1.6"/><path d="M8 18v2h8v-2" stroke="currentColor" stroke-width="1.6"/></svg>
                </span>
                <strong>提交售后申请</strong>
                <span>故障报修、配件咨询与远程技术支持</span>
            </a>
        </div>
    </section>

    <section class="support-docs" id="support-docs">
        <div class="support-docs-shell">
            <div class="support-docs-heading">
                <div class="support-docs-heading__copy">
                    @if($settings->docs_kicker)
                        <p class="support-section-kicker">{{ $settings->docs_kicker }}</p>
                    @endif
                    <h2 class="support-section-title">{{ $settings->docs_title ?: '技术文档资料库' }}</h2>
                </div>
                <div class="support-docs-toolbar">
                    <label class="support-doc-search">
                        <span class="visually-hidden">搜索文档</span>
                        <svg viewBox="0 0 20 20" width="18" height="18" aria-hidden="true"><circle cx="9" cy="9" r="5.5" stroke="currentColor" fill="none"/><path d="M13.5 13.5L17 17" stroke="currentColor" stroke-linecap="round"/></svg>
                        <input type="search" id="supportDocSearch" placeholder="搜索文档名称、分类…" autocomplete="off">
                    </label>
                    <div class="support-doc-filters" role="tablist" aria-label="文档分类筛选">
                        <a class="support-filter-btn {{ $activeDocFilter === 'all' ? 'is-active' : '' }}"
                           href="{{ route('support.index', request()->only('lang')) }}"
                           role="tab" aria-selected="{{ $activeDocFilter === 'all' ? 'true' : 'false' }}">全部</a>
                        @foreach($categories as $cat)
                            <a class="support-filter-btn {{ $activeDocFilter === $cat ? 'is-active' : '' }}"
                               href="{{ route('support.index', array_merge(request()->only('lang'), ['doc_type' => $cat])) }}"
                               role="tab" aria-selected="{{ $activeDocFilter === $cat ? 'true' : 'false' }}">{{ $cat }}</a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="support-doc-list" id="supportDocList">
                @if($documents->isNotEmpty())
                    <div class="support-doc-list__head" aria-hidden="true">
                        <span class="support-doc-list__col support-doc-list__col--name">文档名称</span>
                        <span class="support-doc-list__col support-doc-list__col--category">分类</span>
                        <span class="support-doc-list__col support-doc-list__col--meta">版本 / 信息</span>
                        <span class="support-doc-list__col support-doc-list__col--action">操作</span>
                    </div>
                @endif
                @forelse($documents as $doc)
                    <article class="support-doc-row" data-category="{{ $doc->category }}" data-search="{{ strtolower($doc->title.' '.$doc->category.' '.($doc->version ?? '')) }}">
                        <div class="support-doc-list__col support-doc-list__col--name">
                            <span class="support-doc-row__icon" aria-hidden="true">
                                <svg viewBox="0 0 48 56" width="28" height="32" fill="none"><path d="M8 4h22l10 10v40H8V4z" stroke="currentColor" stroke-width="2"/><path d="M30 4v10h10" stroke="currentColor" stroke-width="2"/></svg>
                            </span>
                            <div class="support-doc-row__main">
                                <h3 class="support-doc-title">{{ $doc->title }}</h3>
                                <p class="support-doc-meta support-doc-meta--mobile">
                                    <span class="support-doc-badge">{{ $doc->category }}</span>
                                    @if($doc->version)<span>{{ $doc->version }}</span>@endif
                                    @if($doc->published_label)<span>{{ $doc->published_label }}</span>@endif
                                    @if($doc->page_count)<span>{{ $doc->page_count }} 页</span>@endif
                                    @if($doc->file_size_label)<span>{{ $doc->file_size_label }}</span>@endif
                                </p>
                            </div>
                        </div>
                        <div class="support-doc-list__col support-doc-list__col--category">
                            <span class="support-doc-badge">{{ $doc->category }}</span>
                        </div>
                        <div class="support-doc-list__col support-doc-list__col--meta">
                            <p class="support-doc-meta">
                                @if($doc->version)<span>{{ $doc->version }}</span>@endif
                                @if($doc->published_label)<span>{{ $doc->published_label }}</span>@endif
                                @if($doc->page_count)<span>{{ $doc->page_count }} 页</span>@endif
                                @if($doc->file_size_label)<span>{{ $doc->file_size_label }}</span>@endif
                                <span class="support-doc-format">PDF</span>
                            </p>
                        </div>
                        <div class="support-doc-list__col support-doc-list__col--action">
                            @if(filled($doc->file_path))
                                <a class="support-doc-download" href="{{ $doc->downloadUrl() }}" target="_blank" rel="noopener"
                                   data-doc-title="{{ $doc->title }}">
                                    <svg viewBox="0 0 20 20" width="16" height="16" aria-hidden="true"><path d="M10 3v9m0 0l-3-3m3 3l3-3M4 14v2h12v-2" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round"/></svg>
                                    <span>下载</span>
                                </a>
                            @else
                                <span class="support-doc-download support-doc-download--disabled">整理中</span>
                            @endif
                        </div>
                    </article>
                @empty
                    <p class="support-empty">暂无文档，请在后台「技术支持 → PDF 文档」中上传资料。</p>
                @endforelse
            </div>
            <p class="support-doc-search-empty" id="supportDocSearchEmpty" hidden>未找到匹配的文档，请更换关键词或分类。</p>
        </div>
    </section>

    <section class="support-videos" id="support-videos" data-video-play-url="{{ url('/support/videos') }}">
        <div class="support-videos-shell">
            @if($settings->videos_kicker)
                <p class="support-section-kicker">{{ $settings->videos_kicker }}</p>
            @endif
            <h2 class="support-section-title">{{ $settings->videos_title ?: '视频教程中心' }}</h2>
            <div class="support-video-list">
                @if($videos->isNotEmpty())
                    <div class="support-video-list__head" aria-hidden="true">
                        <span class="support-video-list__col support-video-list__col--video">视频</span>
                        <span class="support-video-list__col support-video-list__col--info">标题 / 标签</span>
                        <span class="support-video-list__col support-video-list__col--stats">时长 / 播放</span>
                        <span class="support-video-list__col support-video-list__col--action">操作</span>
                    </div>
                @endif
                @forelse($videos as $video)
                    <article class="support-video-row">
                        <div class="support-video-list__col support-video-list__col--video">
                            <div class="support-video-thumb">
                                @if($video->cover_image)
                                    <x-responsive-image
                                        :pc="$video->cover_image"
                                        :mobile="$video->cover_image_mobile"
                                        :alt="$video->title"
                                        loading="lazy"
                                    />
                                @endif
                                <button class="support-video-play" type="button"
                                        data-video-id="{{ $video->id }}"
                                        data-video-title="{{ $video->title }}"
                                        data-video-url="{{ $video->streamUrl() }}"
                                        aria-label="播放 {{ $video->title }}">
                                    <svg viewBox="0 0 48 48" width="36" height="36" aria-hidden="true"><circle cx="24" cy="24" r="24" fill="rgba(0,168,90,.92)"/><path d="M20 16l16 8-16 8V16z" fill="#fff"/></svg>
                                </button>
                            </div>
                        </div>
                        <div class="support-video-list__col support-video-list__col--info">
                            <h3 class="support-video-title">{{ $video->title }}</h3>
                            <div class="support-video-tags">
                                @if($video->tag)
                                    <span class="support-video-tag">{{ $video->tag }}</span>
                                @endif
                                @if($video->duration_label)
                                    <span class="support-video-duration support-video-duration--inline">{{ $video->duration_label }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="support-video-list__col support-video-list__col--stats">
                            @if($video->duration_label)
                                <span class="support-video-stat">{{ $video->duration_label }}</span>
                            @endif
                            <span class="support-video-play-count" data-video-id="{{ $video->id }}">播放 {{ $video->play_count }}</span>
                        </div>
                        <div class="support-video-list__col support-video-list__col--action">
                            <button class="support-video-link" type="button"
                                    data-video-id="{{ $video->id }}"
                                    data-video-title="{{ $video->title }}"
                                    data-video-url="{{ $video->streamUrl() }}">
                                立即播放
                                <svg viewBox="0 0 16 16" width="14" height="14" aria-hidden="true"><path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" fill="none" stroke-width="1.5"/></svg>
                            </button>
                        </div>
                    </article>
                @empty
                    <p class="support-empty">暂无教学视频，请在后台「技术支持 → 教学视频」中添加。</p>
                @endforelse
            </div>
        </div>
    </section>

    <section class="support-service" id="support-service">
        <div class="support-service-shell">
            <aside class="support-contact-card">
                <div class="support-contact-card-top">
                    <div class="support-contact-main-icon" aria-hidden="true">
                        <svg viewBox="0 0 48 48" width="40" height="40"><path d="M8 20v16h32V20M24 8v24M16 16l8-8 8 8" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/></svg>
                    </div>
                    <h2>{!! nl2br(e($settings->contact_title ?: "7×24 售后\n全程专人跟进")) !!}</h2>
                    <p class="support-contact-card__desc">智能设备现场部署、远程诊断与备件支持，由专业技术团队全程响应。</p>
                </div>
                <div class="support-contact-list">
                    @if($settings->contact_phone)
                        <div class="support-contact-row">
                            <span class="support-contact-icon support-contact-icon--phone" aria-hidden="true">
                                <svg viewBox="0 0 24 24" width="20" height="20"><path d="M6 4h4l2 5-2 1a11 11 0 005 5l1-2 5 2v4c0 1-6 2-14-10-14z" stroke="currentColor" fill="none"/></svg>
                            </span>
                            <div>
                                <p>{{ $settings->contact_phone_label ?: '全国热线' }}</p>
                                <strong><a href="tel:{{ preg_replace('/\s+/', '', $settings->contact_phone) }}">{{ $settings->contact_phone }}</a></strong>
                            </div>
                        </div>
                    @endif
                    @if($settings->contact_email)
                        <div class="support-contact-row">
                            <span class="support-contact-icon support-contact-icon--mail" aria-hidden="true">
                                <svg viewBox="0 0 24 24" width="20" height="20"><rect x="3" y="5" width="18" height="14" rx="2" stroke="currentColor" fill="none"/><path d="M3 7l9 6 9-6" stroke="currentColor" fill="none"/></svg>
                            </span>
                            <div>
                                <p>{{ $settings->contact_email_label ?: '技术邮箱' }}</p>
                                <strong><a href="mailto:{{ $settings->contact_email }}">{{ $settings->contact_email }}</a></strong>
                            </div>
                        </div>
                    @endif
                    @if($settings->contact_address)
                        <div class="support-contact-row">
                            <span class="support-contact-icon support-contact-icon--location" aria-hidden="true">
                                <svg viewBox="0 0 24 24" width="20" height="20"><path d="M12 21s7-4.5 7-11a7 7 0 10-14 0c0 6.5 7 11 7 11z" stroke="currentColor" fill="none"/><circle cx="12" cy="10" r="2.5" stroke="currentColor" fill="none"/></svg>
                            </span>
                            <div>
                                <p>{{ $settings->contact_address_label ?: '总部地址' }}</p>
                                <strong>{{ $settings->contact_address }}</strong>
                            </div>
                        </div>
                    @endif
                </div>
            </aside>

            <div class="support-form-panel">
                @if($settings->service_kicker)
                    <p class="support-section-kicker">{{ $settings->service_kicker }}</p>
                @endif
                <h2 class="support-form-title">{{ $settings->service_form_title ?: '提交售后服务申请' }}</h2>
                <p class="support-section-desc support-section-desc--compact">填写设备与问题信息，我们将安排工程师尽快联系您。</p>
                <form class="support-form" id="supportRequestForm" novalidate>
                    @csrf
                    <div class="support-form-grid">
                        <label class="support-form-field">
                            <span>姓名 *</span>
                            <span class="support-input-wrap">
                                <input type="text" name="name" placeholder="请输入姓名" required>
                            </span>
                        </label>
                        <label class="support-form-field">
                            <span>联系电话 *</span>
                            <span class="support-input-wrap">
                                <input type="tel" name="phone" placeholder="手机号码" required>
                            </span>
                        </label>
                        <label class="support-form-field">
                            <span>电子邮箱</span>
                            <span class="support-input-wrap">
                                <input type="email" name="email" placeholder="邮箱地址">
                            </span>
                        </label>
                        <label class="support-form-field support-form-field-region">
                            <span>所在地区 *</span>
                            <span class="support-input-wrap support-input-wrap-region" data-region-mode="picker">
                                <span class="support-region-picker">
                                    <select class="support-region-select" id="supportProvince">
                                        <option value="">选择省份</option>
                                        @foreach($provinces as $p)
                                            <option value="{{ $p['code'] }}">{{ $p['name'] }}</option>
                                        @endforeach
                                    </select>
                                    <span class="support-region-sep">/</span>
                                    <select class="support-region-select" id="supportCity" disabled>
                                        <option value="">选择城市</option>
                                    </select>
                                    <span class="support-region-sep">/</span>
                                    <select class="support-region-select" id="supportDistrict" disabled>
                                        <option value="">选择区县</option>
                                    </select>
                                </span>
                                <input type="hidden" name="province_code" id="supportProvinceCode">
                                <input type="hidden" name="city_code" id="supportCityCode">
                                <input type="hidden" name="district_code" id="supportDistrictCode">
                                <input type="hidden" name="region" id="supportRegionValue" required>
                            </span>
                        </label>
                    </div>
                    <div class="support-form-topics">
                        <span class="support-form-topics-label">咨询主题 *</span>
                        <div class="support-topic-group" role="radiogroup" aria-label="咨询主题">
                            @foreach($settings->topicOptions() as $i => $topic)
                                <button class="support-topic-btn {{ $i === 0 ? 'is-active' : '' }}" type="button"
                                        data-topic="{{ $topic }}" aria-pressed="{{ $i === 0 ? 'true' : 'false' }}">{{ $topic }}</button>
                            @endforeach
                        </div>
                        <input type="hidden" name="topic" value="{{ $settings->topicOptions()[0] ?? '产品使用咨询' }}">
                    </div>
                    <button class="support-submit-btn" type="submit">提交服务申请</button>
                    <p class="support-form-feedback" id="supportFormFeedback" aria-live="polite" hidden></p>
                </form>
            </div>
        </div>
    </section>
</div>

<div class="support-toast" id="supportToast" aria-live="polite"></div>

<div class="support-video-modal" id="supportVideoModal" hidden>
    <div class="support-video-modal__backdrop" data-video-modal-close></div>
    <div class="support-video-modal__dialog">
        <button type="button" class="support-video-modal__close" data-video-modal-close aria-label="关闭">×</button>
        <h3 class="support-video-modal__title" id="supportVideoModalTitle"></h3>
        <video id="supportVideoPlayer" controls playsinline></video>
    </div>
</div>
@endsection

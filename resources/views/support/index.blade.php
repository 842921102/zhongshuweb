@extends('layouts.home')

@section('body_class', 'support-page news-page')

@php
    $heroPc = media_url($settings->hero_image_pc);
    $heroMobile = media_url($settings->hero_image_mobile) ?: $heroPc;
    $heroHeight = $settings->hero_height ?: 450;
    $pageTitle = $settings->meta_title ?: ('技术支持 - '.$siteName);
@endphp

@push('head')
    <title>{{ $pageTitle }}</title>
    @if($settings->meta_description)
        <meta name="description" content="{{ $settings->meta_description }}">
    @endif
    @if($settings->meta_keywords)
        <meta name="keywords" content="{{ $settings->meta_keywords }}">
    @endif
    <link rel="stylesheet" href="{{ asset('css/support.css') }}">
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
    <script src="{{ asset('js/support.js') }}" defer></script>
@endpush

@section('content')
<div class="support-main new_xz">
    <section class="support-hero site-responsive-hero" style="height:{{ $heroHeight }}px;min-height:{{ $heroHeight }}px">
        <div class="support-hero-media">
            <x-responsive-image
                :pc="$settings->hero_image_pc"
                :mobile="$settings->hero_image_mobile"
                class="support-hero-media__img"
                alt="技术支持"
                decoding="async"
            />
        </div>
        <div class="support-hero-container site-responsive-hero" style="height:{{ $heroHeight }}px;min-height:{{ $heroHeight }}px"></div>
    </section>

    <section class="support-docs">
        <div class="support-docs-shell">
            <div class="support-docs-heading">
                <div>
                    @if($settings->docs_kicker)
                        <p class="support-section-kicker support-reveal">{{ $settings->docs_kicker }}</p>
                    @endif
                    <h2 class="support-section-title support-reveal">{{ $settings->docs_title ?: 'PDF 技术文档下载' }}</h2>
                    <div class="support-section-divider support-reveal"></div>
                </div>
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

            <div class="support-doc-list" id="supportDocList">
                @forelse($documents as $doc)
                    <article class="support-doc-item support-reveal" data-category="{{ $doc->category }}">
                        <div class="support-doc-icon-wrap" aria-hidden="true">
                            <svg viewBox="0 0 48 56" width="40" height="48" fill="none"><path d="M8 4h22l10 10v40H8V4z" stroke="currentColor" stroke-width="2"/><path d="M30 4v10h10" stroke="currentColor" stroke-width="2"/></svg>
                        </div>
                        <div class="support-doc-body">
                            <div class="support-doc-meta-top">
                                <span class="support-doc-badge">{{ $doc->category }}</span>
                                <span class="support-doc-format">PDF</span>
                            </div>
                            <h3 class="support-doc-title">{{ $doc->title }}</h3>
                            <p class="support-doc-meta">
                                @if($doc->version)<span>{{ $doc->version }}</span>@endif
                                @if($doc->published_label)<span>{{ $doc->published_label }}</span>@endif
                                @if($doc->page_count)<span>{{ $doc->page_count }}页</span>@endif
                            </p>
                        </div>
                        <div class="support-doc-action">
                            @if($doc->file_size_label)
                                <span class="support-doc-size">{{ $doc->file_size_label }}</span>
                            @endif
                            @if(filled($doc->file_path))
                                <a class="support-doc-download" href="{{ $doc->downloadUrl() }}" target="_blank" rel="noopener"
                                   data-doc-title="{{ $doc->title }}">
                                    <svg viewBox="0 0 20 20" width="18" height="18" aria-hidden="true"><path d="M10 3v9m0 0l-3-3m3 3l3-3M4 14v2h12v-2" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round"/></svg>
                                    <span>下载 PDF</span>
                                </a>
                            @endif
                        </div>
                    </article>
                @empty
                    <p class="support-empty">暂无文档，请在后台「技术支持」中添加 PDF 资料。</p>
                @endforelse
            </div>
        </div>
    </section>

    <section class="support-videos" data-video-play-url="{{ url('/support/videos') }}">
        <div class="support-videos-shell">
            @if($settings->videos_kicker)
                <p class="support-section-kicker support-reveal">{{ $settings->videos_kicker }}</p>
            @endif
            <h2 class="support-section-title support-reveal">{{ $settings->videos_title ?: '视频教程中心' }}</h2>
            <div class="support-section-divider support-reveal"></div>
            <div class="support-video-grid">
                @forelse($videos as $video)
                    <article class="support-video-card support-reveal">
                        <div class="support-video-media">
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
                                    data-video-url="{{ $video->streamUrl() }}">
                                <svg viewBox="0 0 48 48" width="48" height="48" aria-hidden="true"><circle cx="24" cy="24" r="24" fill="rgba(0,168,90,.9)"/><path d="M20 16l16 8-16 8V16z" fill="#fff"/></svg>
                            </button>
                            @if($video->duration_label)
                                <span class="support-video-duration">{{ $video->duration_label }}</span>
                            @endif
                            @if($video->tag)
                                <span class="support-video-tag">{{ $video->tag }}</span>
                            @endif
                        </div>
                        <div class="support-video-body">
                            <h3 class="support-video-title">{{ $video->title }}</h3>
                            <div class="support-video-meta">
                                <span class="support-video-play-count" data-video-id="{{ $video->id }}">播放量 {{ $video->play_count }}</span>
                                <button class="support-video-link" type="button"
                                        data-video-id="{{ $video->id }}"
                                        data-video-title="{{ $video->title }}"
                                        data-video-url="{{ $video->streamUrl() }}">
                                    立即播放
                                    <svg viewBox="0 0 16 16" width="14" height="14" aria-hidden="true"><path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" fill="none" stroke-width="1.5"/></svg>
                                </button>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="support-empty">暂无教学视频。</p>
                @endforelse
            </div>
        </div>
    </section>

    <section class="support-service">
        <div class="support-service-shell">
            <aside class="support-contact-card support-reveal">
                <div class="support-contact-card-top">
                    <div class="support-contact-main-icon" aria-hidden="true">
                        <svg viewBox="0 0 48 48" width="40" height="40"><path d="M8 20v16h32V20M24 8v24M16 16l8-8 8 8" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/></svg>
                    </div>
                    <h2>{!! nl2br(e($settings->contact_title ?: "7×24 售后\n全程专人跟进")) !!}</h2>
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

            <div class="support-form-panel support-reveal">
                @if($settings->service_kicker)
                    <p class="support-section-kicker">{{ $settings->service_kicker }}</p>
                @endif
                <h2 class="support-form-title">{{ $settings->service_form_title ?: '提交售后服务申请' }}</h2>
                <div class="support-section-divider"></div>
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

@extends('layouts.home')

@section('body_class', 'joinus-page news-page')

@php
    $s = $pageSettings;
    $heroImage = media_url($s->hero_image);
    $cultureImage = media_url($s->culture_image);
    $pageTitle = $s->meta_title ?: ('加入我们 - '.$siteName);
    $mailto = $s->contact_email ? 'mailto:'.$s->contact_email : '#contact';
@endphp

@push('head')
    <title>{{ $pageTitle }}</title>
    @if($s->meta_description)
        <meta name="description" content="{{ $s->meta_description }}">
    @endif
    @if($s->meta_keywords)
        <meta name="keywords" content="{{ $s->meta_keywords }}">
    @endif
    <link rel="stylesheet" href="{{ asset('css/join-us.css') }}">
@endpush

@push('scripts')
    <script id="joinPageData" type="application/json">@json($joinPageJson)</script>
    <script>
        window.joinApplyConfig = {
            submitUrl: @json(route('joinus.apply', request()->only('lang'))),
            csrfToken: @json(csrf_token()),
            errorMessage: @json($s->form_error_message ?: '提交失败，请稍后重试'),
        };
    </script>
    <script src="{{ asset('js/join-us.js') }}" defer></script>
@endpush

@section('content')
<div class="join-main">
    <section class="join-hero" @if($heroImage) style="--join-hero-image:url('{{ $heroImage }}')" @endif>
        <div class="join-shell">
            <div class="join-hero-content">
                @if($s->hero_eyebrow)
                    <div class="join-eyebrow">{{ $s->hero_eyebrow }}</div>
                @endif
                <h1>
                    {{ $s->hero_title }}
                    @if($s->hero_title_highlight)
                        <span>{{ $s->hero_title_highlight }}</span>
                    @endif
                </h1>
                @if($s->hero_description)
                    <p>{{ $s->hero_description }}</p>
                @endif
                <div class="join-hero-actions">
                    <a class="join-btn" href="#jobs">{{ $s->hero_cta_primary ?: '查看开放岗位' }}</a>
                    <a class="join-btn join-btn-ghost" href="#contact">{{ $s->hero_cta_secondary ?: '投递简历' }}</a>
                </div>
            </div>
        </div>
    </section>

    @if($whyCards->isNotEmpty())
        <section class="join-section join-section-gray">
            <div class="join-shell">
                <div class="join-section-head join-section-head-center">
                    @if($s->why_kicker)<div class="join-kicker">{{ $s->why_kicker }}</div>@endif
                    @if($s->why_title)<h2>{{ $s->why_title }}</h2>@endif
                    @if($s->why_subtitle)<p>{{ $s->why_subtitle }}</p>@endif
                </div>
                <div class="join-why-grid">
                    @foreach($whyCards as $card)
                        <article class="join-why-card">
                            @if($card->icon_char)<div class="join-why-icon">{{ $card->icon_char }}</div>@endif
                            <h3>{{ $card->title }}</h3>
                            @if($card->description)<p>{{ $card->description }}</p>@endif
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if($cultureCards->isNotEmpty() || $cultureImage)
        <section class="join-section">
            <div class="join-shell">
                <div class="join-section-head">
                    @if($s->culture_kicker)<div class="join-kicker">{{ $s->culture_kicker }}</div>@endif
                    @if($s->culture_title)<h2>{{ $s->culture_title }}</h2>@endif
                    @if($s->culture_subtitle)<p>{{ $s->culture_subtitle }}</p>@endif
                </div>
                <div class="join-culture-layout">
                    <div class="join-culture-image" @if($cultureImage) style="background-image:url('{{ $cultureImage }}')" @endif aria-hidden="true"></div>
                    <div class="join-culture-cards">
                        @foreach($cultureCards as $card)
                            <article class="join-culture-card">
                                @if($card->step_label)<strong>{{ $card->step_label }}</strong>@endif
                                <h3>{{ $card->title }}</h3>
                                @if($card->description)<p>{{ $card->description }}</p>@endif
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section class="join-section join-section-gray" id="jobs">
        <div class="join-shell">
            <div class="join-section-head">
                @if($s->jobs_kicker)<div class="join-kicker">{{ $s->jobs_kicker }}</div>@endif
                @if($s->jobs_title)<h2>{{ $s->jobs_title }}</h2>@endif
                @if($s->jobs_subtitle)<p>{{ $s->jobs_subtitle }}</p>@endif
            </div>
            <div class="join-jobs-layout">
                @if($categories->isNotEmpty())
                    <aside class="join-job-filter" aria-label="岗位分类">
                        <a href="{{ route('joinus.index', request()->only('lang')) }}"
                           class="join-job-filter-link {{ $activeCategorySlug === 'all' ? 'is-active' : '' }}"
                           data-join-category="all">{{ $s->all_jobs_label ?: '全部岗位' }}</a>
                        @foreach($categories as $cat)
                            <a href="{{ route('joinus.index', array_merge(request()->only('lang'), ['category' => $cat->slug])) }}"
                               class="join-job-filter-link {{ $activeCategorySlug === $cat->slug ? 'is-active' : '' }}"
                               data-join-category="{{ $cat->slug }}">{{ $cat->name }}</a>
                        @endforeach
                    </aside>
                @endif
                <div class="join-job-list" id="joinJobList">
                    @forelse($positions as $job)
                        <article class="join-job-card" data-join-category="{{ $job->category?->slug ?? '' }}">
                            <div class="join-job-top">
                                <div class="join-job-title">
                                    <h3>{{ $job->title }}</h3>
                                    @if($job->metaItems() !== [])
                                        <div class="join-job-meta">
                                            @foreach($job->metaItems() as $meta)
                                                <span>{{ $meta }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <a class="join-job-apply" href="#contact" data-position-id="{{ $job->id }}">{{ $s->apply_label ?: '立即投递' }}</a>
                            </div>
                            @if($job->summary)<p>{{ $job->summary }}</p>@endif
                            @if($job->tagList() !== [])
                                <div class="join-job-tags">
                                    @foreach($job->tagList() as $tag)
                                        <span>{{ $tag }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </article>
                    @empty
                        <p class="join-empty">暂无开放岗位</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    @if($processSteps->isNotEmpty())
        <section class="join-section join-section-dark">
            <div class="join-shell">
                <div class="join-section-head join-section-head-center">
                    @if($s->process_kicker)<div class="join-kicker">{{ $s->process_kicker }}</div>@endif
                    @if($s->process_title)<h2>{{ $s->process_title }}</h2>@endif
                    @if($s->process_subtitle)<p>{{ $s->process_subtitle }}</p>@endif
                </div>
                <div class="join-process-grid">
                    @foreach($processSteps as $step)
                        <article class="join-process-card">
                            <h3>{{ $step->title }}</h3>
                            @if($step->description)<p>{{ $step->description }}</p>@endif
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if($welfareCards->isNotEmpty())
        <section class="join-section">
            <div class="join-shell">
                <div class="join-section-head join-section-head-center">
                    @if($s->welfare_kicker)<div class="join-kicker">{{ $s->welfare_kicker }}</div>@endif
                    @if($s->welfare_title)<h2>{{ $s->welfare_title }}</h2>@endif
                    @if($s->welfare_subtitle)<p>{{ $s->welfare_subtitle }}</p>@endif
                </div>
                <div class="join-welfare-grid">
                    @foreach($welfareCards as $card)
                        <article class="join-welfare-card">
                            <h3>{{ $card->title }}</h3>
                            @if($card->description)<p>{{ $card->description }}</p>@endif
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="join-section join-section-gray" id="contact">
        <div class="join-shell">
            <div class="join-contact-layout">
                <div class="join-contact-panel">
                    <div>
                        @if($s->contact_kicker)<div class="join-kicker">{{ $s->contact_kicker }}</div>@endif
                        @if($s->contact_title)<h2>{{ $s->contact_title }}</h2>@endif
                        @if($s->contact_subtitle)<p>{{ $s->contact_subtitle }}</p>@endif
                        @if($s->contact_email)
                            <a class="join-btn join-btn-mail" href="{{ $mailto }}">{{ $s->send_resume_label ?: '发送邮件' }}</a>
                        @endif
                    </div>
                    <div class="join-contact-info">
                        @if($s->contact_email)<div>投递邮箱：{{ $s->contact_email }}</div>@endif
                        @if($s->contact_phone)<div>联系电话：{{ $s->contact_phone }}</div>@endif
                        @if($s->contact_locations)<div>工作地点：{{ $s->contact_locations }}</div>@endif
                        @if($s->contact_email_subject_tip)<div>邮件标题：{{ $s->contact_email_subject_tip }}</div>@endif
                    </div>
                </div>

                <div class="join-apply-card">
                    <h3 class="join-apply-title">{{ $s->form_title ?: '在线投递简历' }}</h3>
                    <form class="join-apply-form" id="joinApplyForm" novalidate>
                        <div class="join-form-row">
                            <label class="join-form-field">
                                <span>意向岗位</span>
                                <select name="position_id" id="joinPositionId">
                                    <option value="">请选择（可选）</option>
                                    @foreach($positionOptions as $pos)
                                        <option value="{{ $pos->id }}">{{ $pos->title }}</option>
                                    @endforeach
                                </select>
                            </label>
                            <label class="join-form-field">
                                <span>姓名 <em>*</em></span>
                                <input type="text" name="name" required maxlength="80" autocomplete="name">
                            </label>
                        </div>
                        <div class="join-form-row">
                            <label class="join-form-field">
                                <span>手机 <em>*</em></span>
                                <input type="tel" name="phone" required maxlength="11" pattern="1[0-9]{10}" autocomplete="tel">
                            </label>
                            <label class="join-form-field">
                                <span>邮箱</span>
                                <input type="email" name="email" maxlength="120" autocomplete="email">
                            </label>
                        </div>
                        <label class="join-form-field">
                            <span>所在城市</span>
                            <input type="text" name="city" maxlength="120">
                        </label>
                        <label class="join-form-field">
                            <span>简历附件 <em>*</em></span>
                            <input type="file" name="resume" accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" required>
                            <small>支持 PDF、Word，不超过 10MB</small>
                        </label>
                        <label class="join-form-field">
                            <span>留言</span>
                            <textarea name="message" rows="3" maxlength="2000" placeholder="可补充工作经历或到岗时间"></textarea>
                        </label>
                        <p class="join-form-feedback" id="joinFormFeedback" hidden></p>
                        <button type="submit" class="join-btn join-btn-block">{{ $s->form_submit_label ?: '提交简历' }}</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@extends('layouts.home')

@section('body_class', 'case-studies-page case-studies-detail header-solid-top')

@section('content')
@push('head')
    <title>{{ $case->meta_title ?: ($case->title.' - '.$siteName) }}</title>
    @if($case->meta_description)
        <meta name="description" content="{{ $case->meta_description }}">
    @endif
    <link rel="stylesheet" href="{{ versioned_asset('css/case-studies.css') }}">
@endpush

<article class="cs-page cs-detail">
    <a class="cs-detail__back" href="{{ route('cases.index', $locale !== 'zh-cn' ? ['lang' => $locale] : []) }}">← 返回客户案例</a>

    @if($case->cover_image)
        <div class="cs-detail__cover">
            <x-responsive-image
                :pc="$case->cover_image"
                :mobile="$case->cover_image_mobile"
                :alt="$case->title"
                fetchpriority="high"
            />
        </div>
    @endif

    <h1>{{ $case->title }}</h1>

    <div class="cs-featured__meta" style="margin-bottom:24px">
        @if($case->region)<span>{{ $case->region }}</span>@endif
        @if($case->sceneLabel())<span>{{ $case->sceneLabel() }}</span>@endif
    </div>

    @if($case->tagList())
        <div class="cs-featured__tags" style="margin-bottom:32px">
            @foreach($case->tagList() as $tag)
                <span>{{ $tag }}</span>
            @endforeach
        </div>
    @endif

    <div class="cs-detail__content cms-rich-content">
        @if($case->content)
            {!! $case->content !!}
        @elseif($case->listExcerpt())
            <p>{{ $case->listExcerpt() }}</p>
        @else
            <p>案例详情内容请在后台「案例列表」中编辑正文。</p>
        @endif
    </div>

    @if($relatedCases->isNotEmpty())
        <section style="margin-top:64px">
            <h2 style="font-size:24px;margin-bottom:24px">相关案例</h2>
            <div class="cs-grid">
                @foreach($relatedCases as $related)
                    <a class="cs-card" href="{{ $related->url() }}">
                        <div class="cs-card__media">
                            <x-responsive-image
                                :pc="$related->cover_image"
                                :mobile="$related->cover_image_mobile"
                                :alt="$related->title"
                                loading="lazy"
                            />
                        </div>
                        <div class="cs-card__body">
                            <h3>{{ $related->title }}</h3>
                            <div class="cs-card__meta">
                                @if($related->region)<span>{{ $related->region }}</span>@endif
                                @if($related->sceneLabel())<span>{{ $related->sceneLabel() }}</span>@endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</article>
@endsection

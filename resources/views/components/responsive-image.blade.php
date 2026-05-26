@props([
    'pc' => null,
    'mobile' => null,
    'fallback' => null,
    'alt' => '',
    'decorative' => false,
    'loading' => null,
    'fetchpriority' => null,
])

@php
    $media = responsive_media($pc, $mobile, $fallback);
    $imgClass = $attributes->get('class', '');
    $extra = $attributes->except(['class', 'pc', 'mobile', 'fallback', 'alt', 'decorative', 'loading', 'fetchpriority']);
@endphp

@if($media['src'])
    @if($media['use_picture'])
        <picture>
            <source media="{{ \App\Support\ResponsiveMedia::mobileMediaQuery() }}" srcset="{{ $media['mobile'] }}">
            <img
                src="{{ $media['pc'] }}"
                alt="{{ $decorative ? '' : $alt }}"
                @if($imgClass !== '') class="{{ $imgClass }}" @endif
                @if($loading) loading="{{ $loading }}" @endif
                @if($fetchpriority) fetchpriority="{{ $fetchpriority }}" @endif
                @if($decorative) aria-hidden="true" @endif
                data-responsive-img
                data-banner-img
                data-banner-pc="{{ $media['pc'] }}"
                data-banner-mobile="{{ $media['mobile'] }}"
                {{ $extra }}
            />
        </picture>
    @else
        <img
            src="{{ $media['src'] }}"
            alt="{{ $decorative ? '' : $alt }}"
            @if($imgClass !== '') class="{{ $imgClass }}" @endif
            @if($loading) loading="{{ $loading }}" @endif
            @if($fetchpriority) fetchpriority="{{ $fetchpriority }}" @endif
            @if($decorative) aria-hidden="true" @endif
            {{ $extra }}
        />
    @endif
@endif

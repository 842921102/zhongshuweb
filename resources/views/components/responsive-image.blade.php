@props([
    'pc' => null,
    'mobile' => null,
    'fallback' => null,
    'alt' => '',
    'decorative' => false,
    'loading' => null,
    'fetchpriority' => null,
    'deferLoad' => false,
    'decoding' => null,
])

@php
    $media = responsive_media($pc, $mobile, $fallback);
    $imgClass = $attributes->get('class', '');
    $extra = $attributes->except(['class', 'pc', 'mobile', 'fallback', 'alt', 'decorative', 'loading', 'fetchpriority', 'deferLoad', 'decoding']);
    $deferLoad = (bool) $deferLoad;
    $placeholderSrc = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';
@endphp

@if($media['src'])
    @if($media['use_picture'])
        <picture>
            <source
                media="{{ \App\Support\ResponsiveMedia::mobileMediaQuery() }}"
                @if($deferLoad)
                    data-deferred-srcset="{{ $media['mobile'] }}"
                @else
                    srcset="{{ $media['mobile'] }}"
                @endif
            >
            <img
                src="{{ $deferLoad ? $placeholderSrc : $media['pc'] }}"
                alt="{{ $decorative ? '' : $alt }}"
                @if($imgClass !== '') class="{{ $imgClass }}" @endif
                @if($deferLoad) data-deferred-src="{{ $media['pc'] }}" @endif
                @if($decoding) decoding="{{ $decoding }}" @endif
                @if($loading) loading="{{ $loading }}" @endif
                @if($fetchpriority) fetchpriority="{{ $fetchpriority }}" @endif
                @if($decorative) aria-hidden="true" @endif
                @if(! $deferLoad) data-responsive-img @endif
                data-banner-img
                data-banner-pc="{{ $media['pc'] }}"
                data-banner-mobile="{{ $media['mobile'] }}"
                {{ $extra }}
            />
        </picture>
    @else
        <img
            src="{{ $deferLoad ? $placeholderSrc : $media['src'] }}"
            alt="{{ $decorative ? '' : $alt }}"
            @if($imgClass !== '') class="{{ $imgClass }}" @endif
            @if($deferLoad) data-deferred-src="{{ $media['src'] }}" @endif
            @if($decoding) decoding="{{ $decoding }}" @endif
            @if($loading) loading="{{ $loading }}" @endif
            @if($fetchpriority) fetchpriority="{{ $fetchpriority }}" @endif
            @if($decorative) aria-hidden="true" @endif
            {{ $extra }}
        />
    @endif
@endif

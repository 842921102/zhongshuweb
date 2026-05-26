@props([
    'pc' => null,
    'mobile' => null,
    'fallback' => null,
])

@php
    $media = responsive_media($pc, $mobile, $fallback);
    $class = $attributes->get('class', '');
    $extra = $attributes->except(['class', 'pc', 'mobile', 'fallback']);
@endphp

@if($media['src'])
    <div
        @if($class !== '') class="{{ $class }}" @endif
        data-responsive-bg
        data-banner-bg
        data-banner-pc="{{ $media['pc'] }}"
        data-banner-mobile="{{ $media['mobile'] ?? '' }}"
        style="background-image:url('{{ $media['src'] }}')"
        {{ $extra }}
    ></div>
@endif

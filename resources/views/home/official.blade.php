@extends('layouts.home')

@push('head')
    <title>{{ $siteName ?? '众鼠科技' }}</title>
    @if($siteDescription ?? null)
        <meta name="description" content="{{ $siteDescription }}">
    @endif
@endpush

@push('preload')
    @if(isset($banners) && $banners->isNotEmpty())
        @php
            $firstBanner = $banners->first();
            $lcpImage = $firstBanner->isVideo()
                ? ($firstBanner->posterPcUrl() ?: media_url($firstBanner->image))
                : media_url($firstBanner->image, media_url($firstBanner->image_mobile));
        @endphp
        @if($lcpImage)
            <link rel="preload" as="image" href="{{ $lcpImage }}" fetchpriority="high">
        @endif
    @endif
@endpush

@section('content')
    @foreach($orderedSections ?? \App\Models\HomeSection::sortedEnabled($sections ?? []) as $section)
        @php($partial = $section->partialView())
        @if($partial)
            @include($partial)
        @endif
    @endforeach
@endsection

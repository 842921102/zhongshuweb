@extends('layouts.home')

@push('head')
    <title>{{ $siteName ?? '众鼠科技' }}</title>
    @if($siteDescription ?? null)
        <meta name="description" content="{{ $siteDescription }}">
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

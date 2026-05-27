@extends('layouts.home')

@section('content')
    @foreach($orderedSections ?? \App\Models\HomeSection::sortedEnabled($sections ?? []) as $section)
        @php($partial = $section->partialView())
        @if($partial)
            @include($partial)
        @endif
    @endforeach
@endsection

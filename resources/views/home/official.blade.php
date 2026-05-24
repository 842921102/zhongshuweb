@extends('layouts.home')

@section('content')
    @if(\App\Models\HomeSection::isEnabledIn($sections, 'hero'))
        @include('home.partials.hero')
    @endif
    @if(\App\Models\HomeSection::isEnabledIn($sections, 'solutions'))
        @include('home.partials.solutions')
    @endif
    @if(\App\Models\HomeSection::isEnabledIn($sections, 'products'))
        @include('home.partials.products')
    @endif
    @if(\App\Models\HomeSection::isEnabledIn($sections, 'cases'))
        @include('home.partials.cases')
    @endif
    @if(\App\Models\HomeSection::isEnabledIn($sections, 'partners'))
        @include('home.partials.partners')
    @endif
    @if(\App\Models\HomeSection::isEnabledIn($sections, 'news'))
        @include('home.partials.news')
    @endif
    @if(\App\Models\HomeSection::isEnabledIn($sections, 'about'))
        @include('home.partials.about')
    @endif
@endsection

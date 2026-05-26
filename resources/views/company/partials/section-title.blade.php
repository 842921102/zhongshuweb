@php
    $eyebrow = $eyebrow ?? null;
    $title = $title ?? null;
    $lead = $lead ?? null;
    $theme = $theme ?? 'light';
@endphp

<div @class([
    'about-section-head',
    'about-title-block',
    'about-title-block--dark' => $theme === 'dark',
])>
    @if(filled($eyebrow))
        <p class="about-title-block__eyebrow">{{ $eyebrow }}</p>
    @endif
    @if(filled($title))
        <h2 class="about-title-block__title">{{ $title }}</h2>
    @endif
</div>
@if(filled($lead))
    <p @class([
        'about-title-block__lead',
        'about-title-block__lead--on-dark' => $theme === 'dark',
    ])>{{ $lead }}</p>
@endif

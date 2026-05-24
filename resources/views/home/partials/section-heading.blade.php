@php
    $sec = $sections[$key] ?? null;
    $title = $sec?->title ?? ($defaultTitle ?? '');
    $highlight = $sec?->title_highlight ?? ($defaultHighlight ?? '');
    $subtitle = $sec?->subtitle ?? ($defaultSubtitle ?? '');
@endphp
<div class="section-heading reveal">
    <h2>{{ $title }}@if($highlight)<span>{{ $highlight }}</span>@endif</h2>
    @if($subtitle)<p>{{ $subtitle }}</p>@endif
</div>

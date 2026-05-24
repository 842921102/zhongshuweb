@php
    /** @var \App\Models\Article $article */
@endphp
<article class="news-card">
    <a href="{{ $article->url() }}" class="news-block-link" aria-labelledby="news-card-title-{{ $article->id }}">
        @if($article->cover_image)
            <div class="news-card-media">
                <img src="{{ media_url($article->cover_image) }}" alt="" width="800" height="534" loading="lazy" decoding="async" aria-hidden="true">
            </div>
        @endif
        <div class="news-card-body">
            <div class="news-meta">
                <img src="{{ asset('home-assets/home-news-calendar.svg') }}" alt="" aria-hidden="true">
                <span>{{ $article->displayDate() }}</span>
            </div>
            <h3 id="news-card-title-{{ $article->id }}" class="news-card-title">{{ $article->title }}</h3>
            @if($article->listSummary())
                <p class="news-card-desc">{{ $article->listSummary() }}</p>
            @endif
            @include('news.partials.readmore', ['label' => $readMoreLabel ?? '阅读全文'])
        </div>
    </a>
</article>

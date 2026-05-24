@if($articles->isNotEmpty())
<section class="section news screen-section" id="home-news">
    <div class="site-shell">
        @include('home.partials.section-heading', [
            'key' => 'news',
            'defaultTitle' => '了解',
            'defaultHighlight' => '最新动态',
            'defaultSubtitle' => '关注众鼠科技新闻资讯，了解智能清洁行业趋势。',
        ])

        <div class="news__grid">
            @foreach($articles as $article)
                <article class="news-card reveal">
                    <div class="news-card__media">
                        <img src="{{ media_url($article->cover_image, asset('home-assets/69eb3db6a3bc9.png')) }}" alt="{{ $article->title }}" loading="lazy">
                    </div>
                    <div class="news-card__body">
                        <div class="news-card__meta">
                            <img src="{{ asset('home-assets/home-news-calendar.svg') }}" alt="">
                            <span>{{ $article->published_at?->format('Y-m-d') }}</span>
                        </div>
                        <h3 class="news-card__title">{{ $article->title }}</h3>
                        <p class="news-card__summary">{{ $article->summary }}</p>
                        <a class="news-card__link" href="{{ $article->url() }}">
                            阅读全文 <img src="{{ asset('home-assets/home-link-arrow.svg') }}" alt="">
                        </a>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="news__actions reveal">
            <a class="button button--primary news__button" href="{{ localized_route('news.index', [], $locale ?? null) }}">查看全部新闻</a>
        </div>
    </div>
</section>
@endif

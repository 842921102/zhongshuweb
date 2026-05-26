@if($cases->isNotEmpty())
<section class="section case-studies screen-section" id="home-case">
    <div class="site-shell">
        @include('home.partials.section-heading', [
            'key' => 'cases',
            'defaultTitle' => '全国'.$cases->count().'+',
            'defaultHighlight' => '项目成功落地',
            'defaultSubtitle' => '服务覆盖全国主要城市，赢得客户广泛认可与信赖。',
        ])

        <article class="case-card reveal">
            @foreach($cases as $index => $case)
                <div class="case-card__slide {{ $index === 0 ? 'is-active' : '' }}" data-case-slide>
                    <x-responsive-image
                        :pc="$case->cover_image"
                        :mobile="$case->cover_image_mobile"
                        :alt="$case->title"
                        class="case-card__base"
                        loading="lazy"
                    />
                    <div class="case-card__shade"></div>
                    <div class="case-card__content">
                        <h3>{{ $case->title }}</h3>
                        <p>{{ $case->region }}</p>
                        <span>{{ $case->scene_type }}</span>
                        <a href="{{ $case->url() }}">案例详情</a>
                    </div>
                </div>
            @endforeach
            @if($cases->count() > 1)
                <button class="case-card__arrow case-card__arrow--prev" type="button" aria-label="上一案例" data-case-prev>
                    <img src="{{ asset('home-assets/home-case-prev.svg') }}" alt="">
                </button>
                <button class="case-card__arrow case-card__arrow--next" type="button" aria-label="下一案例" data-case-next>
                    <img src="{{ asset('home-assets/home-case-next.svg') }}" alt="">
                </button>
                <div class="case-card__dots" aria-hidden="true">
                    @foreach($cases as $index => $case)
                        <button type="button" class="case-card__dot {{ $index === 0 ? 'is-active' : '' }}" data-case-dot data-case-index="{{ $index }}" aria-label="切换到第 {{ $index + 1 }} 个案例"></button>
                    @endforeach
                </div>
            @endif
        </article>
    </div>
</section>
@endif

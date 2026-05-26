<section class="section solutions screen-section" id="home-solutions">
    <div class="site-shell">
        @include('home.partials.section-heading', [
            'key' => 'solutions',
            'defaultTitle' => '全场景智能清洁',
            'defaultHighlight' => '解决方案',
            'defaultSubtitle' => '从城市道路到室内空间，从垃圾收集到数据管理，提供完整的智能环卫生态系统。',
        ])

        @if($solutionsFeatured)
            <article class="solutions-hero solutions-hero--fullbleed reveal">
                <x-responsive-image
                    :pc="$solutionsFeatured->coverPcPath()"
                    :mobile="$solutionsFeatured->coverMobilePath()"
                    :fallback="$solutionsFeatured->icon"
                    :alt="$solutionsFeatured->name"
                    class="solutions-hero__image"
                    loading="lazy"
                />
                <div class="solutions-hero__content">
                    <h3>{{ $solutionsFeatured->name }}</h3>
                    <p>{{ $solutionsFeatured->subtitle }}</p>
                    <a class="button button--primary" href="{{ $solutionsFeatured->link ?: '#home-products' }}">了解详情</a>
                </div>
            </article>
        @endif

        @if($solutionsGrid->isNotEmpty())
            <div class="solutions-grid">
                @foreach($solutionsGrid as $category)
                    <article class="solution-card reveal">
                        <x-responsive-image
                            :pc="$category->coverPcPath()"
                            :mobile="$category->coverMobilePath()"
                            :fallback="$category->icon"
                            :alt="$category->name"
                            loading="lazy"
                        />
                        <div class="solution-card__content">
                            <h3>{{ $category->name }}</h3>
                            <span>{{ $category->subtitle }}</span>
                            <a class="button button--primary button--small" href="{{ $category->link ?: '#home-products' }}">了解详情</a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</section>

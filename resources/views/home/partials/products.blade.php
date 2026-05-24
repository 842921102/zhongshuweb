@if($stationPanels->isNotEmpty())
<section class="section products screen-section" id="home-products">
    <div class="site-shell">
        @include('home.partials.section-heading', [
            'key' => 'products',
            'defaultTitle' => '全系',
            'defaultHighlight' => '产品站',
            'defaultSubtitle' => '创新尖端科技，普惠智慧生活 / 家居智慧新高度 / 全场景智慧清洁方案',
        ])

        <div class="products__tabs reveal" role="tablist" aria-label="产品分类">
            @foreach($stationPanels as $index => $panel)
                <button type="button"
                        class="products__tab {{ $index === 0 ? 'is-active' : '' }}"
                        data-product-tab
                        data-product-key="{{ $panel['tab']->domKey() }}"
                        role="tab"
                        aria-selected="{{ $index === 0 ? 'true' : 'false' }}">{{ $panel['tab']->name }}</button>
            @endforeach
        </div>

        <div class="products__panels">
            @foreach($stationPanels as $index => $panel)
                @php $featured = $panel['featured']; $others = $panel['others']; @endphp
                <div class="products__panel {{ $index === 0 ? 'is-active' : '' }}" data-product-panel="{{ $panel['tab']->domKey() }}">
                    @if($featured)
                        <div class="products__feature reveal">
                            <img src="{{ media_url($featured->displayImage()) }}" alt="{{ $featured->name }}">
                            <div class="products__feature-content">
                                <h3>{{ $featured->model_no ?: $featured->name }}</h3>
                                <p>{{ $featured->subtitle ?: $featured->name }}</p>
                                <strong>{{ $panel['tab']->name }}</strong>
                                @if($featured->detail_url)
                                    <a class="button button--primary bts" href="{{ $featured->detail_url }}">进一步了解</a>
                                @endif
                            </div>
                        </div>
                    @endif
                    <div class="products__list">
                        @foreach($others as $product)
                            <article class="product-mini-card reveal">
                                <img src="{{ media_url($product->displayImage()) }}" alt="{{ $product->name }}">
                                <a class="product-mini-card__content" href="{{ $product->detail_url ?: '#' }}">
                                    <h3>{{ $product->model_no ?: $product->name }}</h3>
                                    <p>{{ $product->subtitle ?: $product->summary }}</p>
                                </a>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

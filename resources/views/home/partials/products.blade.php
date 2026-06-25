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
                            <x-responsive-image
                                :pc="$featured->displayImage()"
                                :mobile="$featured->displayImageMobile()"
                                :alt="$featured->name"
                            />
                            <div class="products__feature-content">
                                <div class="products__feature-panel overlay-copy"@if($style = $featured->overlayCopyStyle()) style="{{ $style }}"@endif>
                                    <h3>{{ $featured->model_no ?: $featured->name }}</h3>
                                    <p>{{ $featured->subtitle ?: $featured->name }}</p>
                                    <strong>{{ $panel['tab']->name }}</strong>
                                    <a class="button button--primary bts" href="{{ $featured->url() }}">了解更多</a>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="products__list">
                        @foreach($others as $product)
                            <article class="product-mini-card reveal">
                                <x-responsive-image
                                    :pc="$product->displayImage()"
                                    :mobile="$product->displayImageMobile()"
                                    :alt="$product->name"
                                    loading="lazy"
                                />
                                <div class="product-mini-card__content">
                                    <div class="product-mini-card__panel overlay-copy"@if($style = $product->overlayCopyStyle()) style="{{ $style }}"@endif>
                                        <h3>{{ $product->model_no ?: $product->name }}</h3>
                                        <span>{{ $product->subtitle ?: $product->summary }}</span>
                                        <a class="button button--primary button--small" href="{{ $product->url() }}">了解更多</a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

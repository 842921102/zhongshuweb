@php
    /** @var \App\Models\Product $product */
    $delay = $delay ?? 0;
    $cardClass = $cardClass ?? 'product-feature-card';
@endphp
<article class="{{ $cardClass }} product-card-enter" style="animation-delay:{{ $delay }}ms">
    <div class="product-card-inner">
    <a href="{{ $product->url() }}" class="product-card-media" aria-label="{{ $product->name }}">
        @if($product->displayImage())
            <img src="{{ media_url($product->displayImage()) }}" alt="{{ $product->name }}" loading="lazy" decoding="async">
        @endif
    </a>
    <div class="product-card-body">
        @if($product->seriesLabel())
            <p class="product-card-series">{{ $product->seriesLabel() }}</p>
        @endif
        <h3 class="product-card-title">{{ $product->name }}</h3>
        @if($product->subtitle)
            <p class="product-card-subtitle">{{ $product->subtitle }}</p>
        @endif
        @if($product->metricPairs())
            <dl class="product-card-metrics">
                @foreach($product->metricPairs() as [$value, $label])
                    <div class="product-card-metric">
                        <dt>{{ $value }}</dt>
                        <dd>{{ $label }}</dd>
                    </div>
                @endforeach
            </dl>
        @endif
        <div class="product-card-foot">
            <a href="{{ $product->url() }}" class="product-card-link">
                <span>{{ $labels['detail'] ?? '查看详情' }}</span>
                <svg class="product-card-link-icon" viewBox="0 0 10 10" fill="none" aria-hidden="true">
                    <path d="M2.9161 2.9161H7.08195V7.08195" stroke="currentColor" stroke-width="0.624878" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2.9161 7.08195L7.08195 2.9161" stroke="currentColor" stroke-width="0.624878" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
        </div>
    </div>
    </div>
</article>

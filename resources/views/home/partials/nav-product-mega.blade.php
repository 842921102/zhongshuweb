@php
    /** @var \App\Models\SiteNavMenu $menu */
@endphp
<div class="site-header__nav-link-wrap site-header__nav-link--product-wrap">
    <a class="site-header__nav-link site-header__nav-link--with-icon"
       href="{{ $menu->href() }}"
       @if($menu->route_keys) data-route="{{ $menu->route_keys }}" @endif
       @if($menu->search_keywords) data-search="{{ $menu->search_keywords }}" @endif
       id="siteHeaderProductNav"
       data-product-trigger
       aria-expanded="false"
       aria-haspopup="true"
       aria-controls="siteHeaderProductDropdown">
        <span>{{ $menu->label }}</span>
        <img src="{{ asset('home-assets/Icon.svg') }}" alt="">
    </a>
    <div class="product-dropdown" id="siteHeaderProductDropdown" data-product-dropdown>
        <div class="product-dropdown-shell">
            <div class="product-dropdown-menu">
                @foreach($productCategories as $i => $cat)
                    <button type="button" class="product-dropdown-category {{ $i === 0 ? 'is-active' : '' }}" data-product-category="{{ $cat['key'] }}">
                        @if(!empty($cat['icon']))
                            <span class="product-dropdown-icon-wrap" style="--product-icon-mask: url('{{ $cat['icon'] }}')">
                                <img src="{{ $cat['icon'] }}" alt="" class="product-dropdown-icon">
                            </span>
                        @endif
                        <span class="product-dropdown-category-text">
                            <strong>{{ $cat['label'] }}</strong>
                            <em>{{ $cat['subtitle'] }}</em>
                        </span>
                        <img src="{{ asset('home-assets/Icon.png') }}" alt="" class="product-dropdown-chevron">
                    </button>
                @endforeach
            </div>
            <div class="product-dropdown-content">
                <div class="product-dropdown-head">
                    <h3 class="product-dropdown-title" data-product-title>{{ $productCategories[0]['label'] ?? '' }}</h3>
                    <a class="product-dropdown-all" data-product-viewall href="{{ localized_route('products.index', [], $locale ?? null) }}">
                        <span>{{ $productNavJson['labels']['view_all'] ?? '查看全部' }}</span>
                        <img src="{{ asset('home-assets/Icon.png') }}" alt="" aria-hidden="true">
                    </a>
                </div>
                <div class="product-dropdown-cards" data-product-cards></div>
            </div>
        </div>
    </div>
</div>

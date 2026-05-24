@php
    $logoDefault = media_url(\App\Models\SiteSetting::get('header_logo_default'), asset('home-assets/69f053450ed94.png'));
    $logoScrolled = media_url(\App\Models\SiteSetting::get('header_logo_scrolled'), asset('home-assets/69f026d932c50.png'));
    $productNavJson = $productNavJson ?? ['categories' => [], 'children' => [], 'labels' => ['view_all' => '查看全部']];
    $productCategories = $productNavJson['categories'] ?? [];
    $navMenus = $navMenus ?? collect();
    $siteName = $siteName ?? '众鼠科技';
@endphp
<header class="site-header" data-header
        data-search-empty="暂无可搜索内容"
        data-search-miss="未找到相关结果"
        data-search-jump-prefix="前往"
        data-search-api="{{ localized_route('api.search', [], $locale ?? null) }}"
        style="--site-header-bg: transparent; --site-header-fg-default: #ffffff; --site-header-icon-filter-default: brightness(0) invert(1); --site-header-bg-scrolled: #ffffff; --site-header-fg-scrolled: #101828; --site-header-icon-filter-scrolled: brightness(0) invert(0); --site-header-logo-filter-scrolled: brightness(0) invert(0); --site-header-btn-bg-scrolled: #101828; --site-header-btn-fg-scrolled: #ffffff; --site-header-btn-border-scrolled: transparent; --site-header-btn-icon-filter-scrolled: brightness(0) invert(1);">
    <div class="site-shell site-header__inner">
        <a class="site-header__brand" href="{{ localized_route('home', [], $locale ?? null) }}" aria-label="前往首页">
            <img class="site-header__brand-logo site-header__brand-logo--default" src="{{ $logoDefault }}" alt="{{ $siteName }}">
            <img class="site-header__brand-logo site-header__brand-logo--scrolled" src="{{ $logoScrolled }}" alt="{{ $siteName }}">
        </a>
        <button class="site-header__toggle" type="button" aria-expanded="false" aria-controls="site-navigation" data-nav-toggle>
            <span></span><span></span><span></span>
        </button>
        <nav class="site-header__nav" id="site-navigation" data-nav>
            @forelse($navMenus as $menu)
                @if($menu->isProductMega())
                    @if(count($productCategories))
                        @include('home.partials.nav-product-mega', ['menu' => $menu, 'productCategories' => $productCategories])
                    @endif
                @else
                    <div class="site-header__nav-link-wrap">
                        <a class="site-header__nav-link"
                           href="{{ $menu->href() }}"
                           @if($menu->route_keys) data-route="{{ $menu->route_keys }}" @endif
                           @if($menu->search_keywords) data-search="{{ $menu->search_keywords }}" @endif
                           @if($menu->open_in_new_tab) target="_blank" rel="noopener noreferrer" @endif>
                            <span>{{ $menu->label }}</span>
                        </a>
                    </div>
                @endif
            @empty
                <div class="site-header__nav-link-wrap">
                    <a class="site-header__nav-link" href="{{ localized_route('home', [], $locale ?? null) }}" data-route="home,index" data-search="首页 home,index"><span>首页</span></a>
                </div>
            @endforelse
        </nav>
        <script id="siteHeaderProductData" type="application/json">@json($productNavJson)</script>
        <div class="site-header__actions">
            <button class="site-header__icon-button" type="button" aria-label="打开搜索" aria-expanded="false" aria-controls="siteHeaderSearchPanel" data-search-toggle>
                <img src="{{ asset('home-assets/home-search.svg') }}" alt="">
            </button>
        </div>
    </div>
    <div class="site-header__search-panel" id="siteHeaderSearchPanel" data-search-panel hidden>
        <div class="site-header__search-shell site-shell">
            <form class="site-header__search-form" data-search-form novalidate>
                <label class="site-header__search-title" for="siteHeaderSearchInput">站内搜索</label>
                <div class="site-header__search-field">
                    <img src="{{ asset('home-assets/home-search.svg') }}" alt="" aria-hidden="true">
                    <input id="siteHeaderSearchInput" class="site-header__search-input" type="search" name="keyword" placeholder="搜索产品、案例、新闻" autocomplete="off" data-search-input>
                    <button class="site-header__search-submit" type="submit">搜索</button>
                </div>
                <p class="site-header__search-status" data-search-status>输入关键词快速找到对应页面。</p>
                <div class="site-header__search-results" data-search-results hidden></div>
            </form>
        </div>
    </div>
</header>

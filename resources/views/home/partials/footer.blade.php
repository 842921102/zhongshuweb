@php
    $footer = $footer ?? [];
    $footerLogo = media_url($footer['logo'] ?? null, asset('home-assets/69e9d8260dd85.png'));
@endphp
<footer class="site-footer new_xz screen-section" id="home-footer" style="background: #101828; --site-footer-bg: #101828; --site-footer-fg: #ffffff; --site-footer-fg-soft: #ffffff; --site-footer-icon-filter: brightness(0) invert(1);">
    <div class="site-shell site-footer__top site-footer__top--desktop">
        <div class="site-footer__brand">
            <a href="{{ localized_route('home', [], $locale ?? null) }}" aria-label="前往首页">
                <img src="{{ $footerLogo }}" alt="{{ $footer['company_name'] }}">
            </a>
            @if($footer['tagline'])
                <p>{{ $footer['tagline'] }}</p>
            @endif
            <ul class="site-footer__contact">
                @if($footer['phone'])
                    <li><img src="{{ asset('home-assets/home-phone.svg') }}" alt=""><span>{{ $footer['phone'] }}</span></li>
                @endif
                @if($footer['email'])
                    <li><img src="{{ asset('home-assets/home-email.svg') }}" alt=""><span>{{ $footer['email'] }}</span></li>
                @endif
                @if($footer['address'])
                    <li><img src="{{ asset('home-assets/home-location.svg') }}" alt=""><span>{{ $footer['address'] }}</span></li>
                @endif
            </ul>
        </div>
        @foreach($footer['link_groups'] as $groupKey => $links)
            <div class="site-footer__links">
                <div>
                    <h3>{{ $links->first()->group_label }}</h3>
                    @foreach($links as $link)
                        <a href="{{ localized_url($link->url ?: '#', $locale ?? null) }}" @if(str_starts_with($link->url ?? '', 'http')) target="_blank" rel="noopener noreferrer" @endif>{{ $link->label }}</a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <div class="site-shell site-footer__mobile" aria-label="页脚移动端导航">
        <div class="site-footer__brand site-footer__brand--mobile">
            <a href="{{ localized_route('home', [], $locale ?? null) }}" aria-label="前往首页">
                <img src="{{ $footerLogo }}" alt="{{ $footer['company_name'] }}">
            </a>
            @if($footer['tagline'])<p>{{ $footer['tagline'] }}</p>@endif
        </div>
        @foreach($footer['link_groups'] as $groupKey => $links)
            <details class="site-footer__accordion">
                <summary>
                    <span>{{ $links->first()->group_label }}</span>
                    <span class="site-footer__accordion-icon" aria-hidden="true"></span>
                </summary>
                <div class="site-footer__accordion-body">
                    @foreach($links as $link)
                        <a href="{{ localized_url($link->url ?: '#', $locale ?? null) }}">{{ $link->label }}</a>
                    @endforeach
                </div>
            </details>
        @endforeach
        <details class="site-footer__accordion" open>
            <summary><span>联系我们</span><span class="site-footer__accordion-icon" aria-hidden="true"></span></summary>
            <div class="site-footer__accordion-body site-footer__accordion-body--contact">
                <ul class="site-footer__contact site-footer__contact--mobile">
                    @if($footer['phone'])<li><img src="{{ asset('home-assets/home-phone.svg') }}" alt=""><span>{{ $footer['phone'] }}</span></li>@endif
                    @if($footer['email'])<li><img src="{{ asset('home-assets/home-email.svg') }}" alt=""><span>{{ $footer['email'] }}</span></li>@endif
                    @if($footer['address'])<li><img src="{{ asset('home-assets/home-location.svg') }}" alt=""><span>{{ $footer['address'] }}</span></li>@endif
                </ul>
            </div>
        </details>
    </div>

    <div class="site-shell site-footer__bottom">
        <p>{{ $footer['copyright'] }}@if($footer['icp']) | {{ $footer['icp'] }}@endif</p>
    </div>
</footer>

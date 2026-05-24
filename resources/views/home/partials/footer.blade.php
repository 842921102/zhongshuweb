@php
    $footer = $footer ?? [];
    $footerLogo = media_url($footer['logo'] ?? null, asset('home-assets/69e9d8260dd85.png'));
    $socialLinks = $footer['social'] ?? [];
@endphp
<footer class="site-footer new_xz screen-section" id="home-footer">
    <div class="site-shell site-footer__top site-footer__top--desktop">
        <div class="site-footer__brand">
            <a href="{{ localized_route('home', [], $locale ?? null) }}" aria-label="前往首页">
                <img src="{{ $footerLogo }}" alt="{{ $footer['company_name'] }}">
            </a>
            @if($footer['tagline'] ?? null)
                <p>{{ $footer['tagline'] }}</p>
            @endif
            <ul class="site-footer__contact">
                @if($footer['phone'] ?? null)
                    <li><img src="{{ asset('home-assets/home-phone.svg') }}" alt="" width="20" height="20"><span>{{ $footer['phone'] }}</span></li>
                @endif
                @if($footer['email'] ?? null)
                    <li><img src="{{ asset('home-assets/home-email.svg') }}" alt="" width="20" height="20"><span>{{ $footer['email'] }}</span></li>
                @endif
                @if($footer['address'] ?? null)
                    <li><img src="{{ asset('home-assets/home-location.svg') }}" alt="" width="20" height="20"><span>{{ $footer['address'] }}</span></li>
                @endif
            </ul>
        </div>
        @foreach($footer['link_groups'] ?? [] as $groupKey => $links)
            <div class="site-footer__col">
                <h3>{{ $links->first()->group_label }}</h3>
                @foreach($links as $link)
                    <a href="{{ localized_url($link->url ?: '#', $locale ?? null) }}"
                       @if(str_starts_with($link->url ?? '', 'http')) target="_blank" rel="noopener noreferrer" @endif>{{ $link->label }}</a>
                @endforeach
            </div>
        @endforeach
    </div>

    <div class="site-shell site-footer__mobile" aria-label="页脚移动端导航">
        <div class="site-footer__brand site-footer__brand--mobile">
            <a href="{{ localized_route('home', [], $locale ?? null) }}" aria-label="前往首页">
                <img src="{{ $footerLogo }}" alt="{{ $footer['company_name'] }}">
            </a>
            @if($footer['tagline'] ?? null)<p>{{ $footer['tagline'] }}</p>@endif
        </div>
        @foreach($footer['link_groups'] ?? [] as $groupKey => $links)
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
                    @if($footer['phone'] ?? null)<li><img src="{{ asset('home-assets/home-phone.svg') }}" alt=""><span>{{ $footer['phone'] }}</span></li>@endif
                    @if($footer['email'] ?? null)<li><img src="{{ asset('home-assets/home-email.svg') }}" alt=""><span>{{ $footer['email'] }}</span></li>@endif
                    @if($footer['address'] ?? null)<li><img src="{{ asset('home-assets/home-location.svg') }}" alt=""><span>{{ $footer['address'] }}</span></li>@endif
                </ul>
            </div>
        </details>
    </div>

    <div class="site-shell site-footer__bottom">
        <p class="site-footer__legal">
            {{ $footer['copyright'] ?? '' }}@if($footer['icp'] ?? null)<span class="site-footer__legal-sep">|</span>{{ $footer['icp'] }}@endif
        </p>
        @if(count($socialLinks))
            <div class="site-footer__social-wrap">
                <div class="site-footer__social" role="list" aria-label="社交媒体">
                    @foreach($socialLinks as $social)
                        @php
                            $iconUrl = media_url($social['icon'] ?? null);
                        @endphp
                        @if(($social['type'] ?? '') === 'qr' && ! empty($social['qr_image']))
                            <button type="button"
                                    class="site-footer__social-btn"
                                    role="listitem"
                                    data-social-qr-trigger
                                    data-social-qr="{{ media_url($social['qr_image']) }}"
                                    data-social-name="{{ $social['name'] ?? '' }}"
                                    aria-label="{{ $social['name'] ?? '扫码关注' }}">
                                <span class="site-footer__social-icon" style="--site-social-icon-url: url('{{ $iconUrl }}')"></span>
                            </button>
                        @elseif(! empty($social['url']))
                            <a href="{{ $social['url'] }}"
                               class="site-footer__social-link"
                               role="listitem"
                               target="_blank"
                               rel="noopener noreferrer"
                               aria-label="{{ $social['name'] ?? '' }}">
                                <span class="site-footer__social-icon" style="--site-social-icon-url: url('{{ $iconUrl }}')"></span>
                            </a>
                        @endif
                    @endforeach
                </div>
                <div class="site-footer__social-qr-panel" data-social-qr-panel hidden>
                    <div class="site-footer__social-qr-image-wrap">
                        <img src="" alt="" data-social-qr-image width="140" height="140">
                    </div>
                </div>
            </div>
        @endif
    </div>
</footer>

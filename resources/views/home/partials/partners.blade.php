<section class="section partners screen-section" id="home-partners">
    <div class="site-shell">
        @include('home.partials.section-heading', [
            'key' => 'partners',
            'defaultTitle' => '携手',
            'defaultHighlight' => '行业伙伴',
            'defaultSubtitle' => '与多家企业和场景方建立合作，共同推动智能清洁设备落地应用。',
        ])

        @if($partners->isNotEmpty())
            <ul class="partners__grid reveal" aria-label="合作伙伴列表">
                @foreach($partners as $partner)
                    <li>
                        <a class="partners__card" href="{{ $partner->link ?: 'javascript:void(0);' }}">
                            @if($partner->logo)
                                <img class="partners__logo" src="{{ media_url($partner->logo) }}" alt="{{ $partner->name }}" loading="lazy">
                            @endif
                            <span class="partners__name">{{ $partner->name }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    @if($statistics->isNotEmpty())
        <div class="partners__band">
            <img src="{{ asset('home-assets/home-partners-bg.png') }}" alt="合作伙伴数据背景" aria-hidden="true" loading="lazy" decoding="async">
            <div class="site-shell">
                <div class="partners__stats" role="list">
                    @foreach($statistics as $stat)
                        <div class="partners__stat reveal" role="listitem">
                            <strong>{{ $stat->displayValue() }}</strong>
                            <span>{{ $stat->label }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</section>

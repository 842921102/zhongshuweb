<section class="section about screen-section" id="home-about">
    <div class="site-shell">
        @include('home.partials.section-heading', [
            'key' => 'about',
            'defaultTitle' => '关于',
            'defaultHighlight' => '众鼠科技',
            'defaultSubtitle' => $about?->subtitle ?? '以智能清洁设备研发制造，推动城市空间服务升级。',
        ])
    </div>
    <div class="about__visual reveal">
        <img src="{{ media_url($about?->cover_image, asset('home-assets/69e9ff102a425.jpg')) }}" alt="{{ $about?->title ?? '关于众鼠科技' }}" class="about__image" loading="lazy">
        @if($about?->excerpt || $about?->content)
            <div class="about__panel">
                <p>{{ $about->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($about->content), 280) }}</p>
                @if($about->button_text)
                    <a class="button button--primary" href="{{ localized_url($about->button_url ?: '/about', $locale ?? null) }}" style="margin-top:16px;display:inline-block">{{ $about->button_text }}</a>
                @elseif($about->button_url)
                    <a class="button button--primary" href="{{ localized_url($about->button_url, $locale ?? null) }}" style="margin-top:16px;display:inline-block">了解我们</a>
                @endif
            </div>
        @endif
    </div>
</section>

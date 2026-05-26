<section class="section about screen-section" id="home-about">
    <div class="site-shell">
        @include('home.partials.section-heading', [
            'key' => 'about',
            'defaultTitle' => '关于',
            'defaultHighlight' => '众鼠科技',
            'defaultSubtitle' => $about?->intro_eyebrow ?? '以智能清洁设备研发制造，推动城市空间服务升级。',
        ])
    </div>
    <div class="about__visual reveal">
        <x-responsive-image
            :pc="$about?->intro_side_image ?: $about?->hero_media_url"
            :mobile="$about?->intro_side_image_mobile ?? $about?->hero_media_mobile ?? null"
            fallback="home-assets/69e9ff102a425.jpg"
            :alt="$about?->intro_title ?? '关于众鼠科技'"
            class="about__image"
            loading="lazy"
        />
        @if($about?->intro_body)
            <div class="about__panel">
                <p>{{ \Illuminate\Support\Str::limit(strip_tags($about->intro_body), 280) }}</p>
                <a class="button button--primary" href="{{ localized_url('/about', $locale ?? null) }}" style="margin-top:16px;display:inline-block">了解我们</a>
            </div>
        @endif
    </div>
</section>

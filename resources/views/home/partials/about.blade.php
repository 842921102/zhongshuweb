@php
    $aboutSection = $sections['about'] ?? null;
    $aboutImagePc = $aboutSection?->visual_image
        ?: ($about?->intro_side_image ?: $about?->hero_media_url);
    $aboutImageMobile = filled($aboutSection?->visual_image_mobile)
        ? $aboutSection->visual_image_mobile
        : ($about?->intro_side_image_mobile ?: $about?->hero_media_mobile);
    $aboutPanelText = filled($aboutSection?->visual_text)
        ? $aboutSection->visual_text
        : ($about?->intro_body ? \Illuminate\Support\Str::limit(strip_tags($about->intro_body), 280) : null);
    $aboutButtonLabel = $aboutSection?->visual_button_label ?: '了解我们';
    $aboutButtonUrl = filled($aboutSection?->visual_button_url)
        ? localized_url($aboutSection->visual_button_url, $locale ?? null)
        : localized_url('/about', $locale ?? null);
    $showAboutPanel = filled($aboutPanelText)
        || filled($aboutSection?->visual_button_label)
        || filled($aboutSection?->visual_button_url);
    $aboutTitle = $aboutSection?->title ?? '关于';
    $aboutHighlight = $aboutSection?->title_highlight ?? '众鼠科技';
    $aboutSubtitle = $aboutSection?->subtitle
        ?? ($about?->intro_eyebrow ?? '以智能清洁设备研发制造，推动城市空间服务升级。');
@endphp
<section class="section about screen-section" id="home-about">
    <div class="about__visual reveal">
        <x-responsive-image
            :pc="$aboutImagePc"
            :mobile="$aboutImageMobile"
            fallback="/home-assets/69e9ff102a425.jpg"
            :alt="$about?->intro_title ?? '关于众鼠科技'"
            class="about__image"
            loading="lazy"
        />
        <div class="about__fade" aria-hidden="true"></div>
        <div class="about__overlay">
            <div class="about__headline copy-interactive copy-interactive--on-dark reveal">
                <h2>{{ $aboutTitle }}@if($aboutHighlight)<span>{{ $aboutHighlight }}</span>@endif</h2>
                @if($aboutSubtitle)
                    <p>{{ $aboutSubtitle }}</p>
                @endif
            </div>
            @if($showAboutPanel)
                <div class="about__panel copy-interactive copy-interactive--on-dark">
                    <div class="about__panel-inner">
                        @if(filled($aboutPanelText))
                            <p class="about__text">{{ $aboutPanelText }}</p>
                        @endif
                        <a class="button button--primary about__cta" href="{{ $aboutButtonUrl }}">{{ $aboutButtonLabel }}</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

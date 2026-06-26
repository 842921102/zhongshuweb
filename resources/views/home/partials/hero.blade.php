@if($banners->isNotEmpty())
<section class="hero screen-section" id="home-hero">
    @foreach($banners as $index => $banner)
        @php
            $posterPc = $banner->posterPcUrl();
            $posterMobile = $banner->posterMobileUrl();
            $isFirst = $index === 0;
        @endphp
        <div class="hero__slide {{ $isFirst ? 'is-active' : '' }}" data-hero-slide data-media-type="{{ $banner->media_type }}">
            <div class="hero__media">
                @if($banner->isVideo() && $banner->videoPcUrl())
                    <video
                        class="hero__video"
                        data-hero-video
                        @if($isFirst) data-hero-intro-video @endif
                        data-banner-poster-pc="{{ $posterPc }}"
                        data-banner-poster-mobile="{{ $posterMobile }}"
                        data-banner-video-pc="{{ $banner->videoPcUrl() }}"
                        data-banner-video-mobile="{{ $banner->videoMobileUrl() }}"
                        @if($posterPc && $isFirst) poster="{{ $posterPc }}" @endif
                        playsinline
                        muted
                        preload="{{ $isFirst ? 'metadata' : 'none' }}"
                        @if($isFirst) autoplay @endif
                        @if(! $isFirst) data-deferred-video="1" @endif
                    >
                        @if($isFirst)
                            <source src="{{ $banner->videoPcUrl() }}" type="video/mp4">
                        @endif
                    </video>
                @else
                    @php
                        $pc = $posterPc;
                        $mobile = $posterMobile;
                    @endphp
                    @if($pc)
                        <x-responsive-image
                            :pc="$banner->image"
                            :mobile="$banner->image_mobile"
                            class="hero__image"
                            decorative
                            :fetchpriority="$isFirst ? 'high' : null"
                            :deferLoad="! $isFirst"
                            :decoding="$isFirst ? 'async' : null"
                        />
                    @endif
                @endif
            </div>
            @if($banner->button_text && $banner->link)
                <div class="site-shell hero__content">
                    <div class="hero__actions">
                        <a class="button button--primary hero__button" href="{{ $banner->link }}">{{ $banner->button_text }}</a>
                    </div>
                </div>
            @endif
        </div>
    @endforeach
    @if($banners->count() > 1)
        <button class="hero__arrow hero__arrow--prev" type="button" aria-label="查看上一屏" data-hero-prev>
            <img src="{{ asset('home-assets/home-hero-prev.svg') }}" alt="">
        </button>
        <button class="hero__arrow hero__arrow--next" type="button" aria-label="查看下一屏" data-hero-next>
            <img src="{{ asset('home-assets/home-hero-next.svg') }}" alt="">
        </button>
        <div class="hero__dots" aria-hidden="true">
            @foreach($banners as $index => $banner)
                <button type="button" class="hero__dot {{ $index === 0 ? 'is-active' : '' }}" data-hero-dot data-hero-index="{{ $index }}" aria-label="切换到第 {{ $index + 1 }} 张"></button>
            @endforeach
        </div>
    @endif
    <button class="hero__scroll" type="button" aria-label="向下滚动探索更多" data-scroll-target="{{ $heroScrollTarget ?? '#home-solutions' }}">
        <span style="letter-spacing: -0.15px;line-height: 20px;">向下滚动探索更多</span>
        <img src="{{ asset('home-assets/home-scroll-down.svg') }}" alt="">
    </button>
</section>
@endif

@php
    $imagePc = $imagePc ?? null;
    $imageMobile = $imageMobile ?? null;
    $videoUrl = $videoUrl ?? null;
    $poster = $poster ?? $imagePc;
    $posterMobile = $posterMobile ?? $imageMobile;
    $alt = $alt ?? '';
    $height = isset($height) && (int) $height > 0 ? (int) $height : null;
    $ariaLabel = $ariaLabel ?? null;
    $videoId = $videoId ?? 'productBannerVideo';
    $hasVideo = filled($videoUrl);
    $hasImage = filled($imagePc);
@endphp
<div class="product-banner" @if($height) style="--product-banner-height: {{ $height }}px" @endif>
    <div class="product-banner__media" @if($ariaLabel) aria-label="{{ $ariaLabel }}" @else aria-hidden="true" @endif>
        @if($hasImage)
            <x-responsive-image
                :pc="$imagePc"
                :mobile="$imageMobile"
                :alt="$alt"
                class="product-banner__image"
                :decorative="$hasVideo"
                :fetchpriority="($fetchpriority ?? false) ? 'high' : null"
            />
        @endif
        @if($hasVideo)
            <video id="{{ $videoId }}"
                   class="product-banner__video"
                   src="{{ $videoUrl }}"
                   @if($poster) poster="{{ $poster }}" @endif
                   data-banner-video-pc="{{ $videoUrl }}"
                   data-banner-video-mobile="{{ $videoMobile ?? $videoUrl }}"
                   @if($poster) data-banner-poster-pc="{{ $poster }}" data-banner-poster-mobile="{{ $posterMobile ?? $poster }}" @endif
                   autoplay muted loop playsinline></video>
            <div class="product-banner__controls">
                <button type="button"
                        class="product-banner__control product-banner__control--play"
                        data-banner-play
                        aria-label="暂停视频">
                    <span class="hero-icon hero-icon-play" aria-hidden="true"></span>
                    <span class="hero-icon hero-icon-pause" aria-hidden="true"></span>
                </button>
                <button type="button"
                        class="product-banner__control product-banner__control--audio is-muted"
                        data-banner-mute
                        aria-label="开启声音">
                    <span class="hero-icon hero-icon-volume" aria-hidden="true"></span>
                    <span class="hero-icon hero-icon-muted" aria-hidden="true"></span>
                </button>
            </div>
        @elseif(!$hasImage)
            <div class="product-banner__placeholder" aria-hidden="true"></div>
        @endif
    </div>
</div>

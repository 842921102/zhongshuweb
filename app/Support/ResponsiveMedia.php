<?php

namespace App\Support;

class ResponsiveMedia
{
    /** 与 site-layout.js、home-common.css --site-bp-nav 保持一致 */
    public const VIEWPORT_BREAKPOINT = 968;

    /**
     * @return array{
     *     src: ?string,
     *     pc: ?string,
     *     mobile: ?string,
     *     use_picture: bool,
     * }
     */
    public static function resolve(?string $pc, ?string $mobile = null, ?string $fallback = null): array
    {
        $pcUrl = MediaUrl::resolve($pc);
        $mobileOnly = filled($mobile) ? MediaUrl::resolve($mobile) : null;
        $mobileUrl = $mobileOnly ?: $pcUrl;
        $src = $pcUrl ?: $mobileOnly ?: MediaUrl::resolve($fallback);

        $usePicture = filled($mobileOnly)
            && filled($pcUrl)
            && $mobileOnly !== $pcUrl;

        return [
            'src' => $src,
            'pc' => $pcUrl ?: $src,
            'mobile' => $usePicture ? $mobileOnly : null,
            'use_picture' => $usePicture,
        ];
    }

    public static function mobileMediaQuery(): string
    {
        return '(max-width: '.self::VIEWPORT_BREAKPOINT.'px)';
    }
}

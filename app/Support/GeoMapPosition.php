<?php

namespace App\Support;

final class GeoMapPosition
{
    /**
     * 等距圆柱粗算坐标（仅作无 JS 时的回退；前台标注以 D3 投影为准）。
     *
     * @return array{x: float, y: float}
     */
    public static function latLonToPercent(float $lat, float $lon): array
    {
        $lat = max(-85.0, min(85.0, $lat));
        $lon = fmod($lon + 180.0, 360.0) - 180.0;

        return [
            'x' => round((($lon + 180.0) / 360.0) * 100, 2),
            'y' => round(((90.0 - $lat) / 180.0) * 100, 2),
        ];
    }

    /**
     * @param  array<string, mixed>  $marker
     * @return array{x: float, y: float}
     */
    public static function resolveMarker(array $marker): array
    {
        if (isset($marker['lat'], $marker['lon']) && is_numeric($marker['lat']) && is_numeric($marker['lon'])) {
            return self::latLonToPercent((float) $marker['lat'], (float) $marker['lon']);
        }

        return [
            'x' => max(0, min(100, (float) ($marker['x'] ?? 50))),
            'y' => max(0, min(100, (float) ($marker['y'] ?? 50))),
        ];
    }
}

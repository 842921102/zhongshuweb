<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

/** @implements CastsAttributes<array<int|string, mixed>, array<int|string, mixed>|string|null> */
class JsonArrayCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): array
    {
        return self::normalize($value);
    }

    public function set($model, string $key, $value, array $attributes): ?string
    {
        if ($value === null || $value === '') {
            return json_encode([], JSON_UNESCAPED_UNICODE);
        }

        $array = is_string($value) ? self::normalize($value) : (array) $value;

        return json_encode($array, JSON_UNESCAPED_UNICODE);
    }

    /** @return array<int|string, mixed> */
    public static function normalize(mixed $value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if ($value === null || $value === '') {
            return [];
        }

        $decoded = $value;

        for ($i = 0; $i < 3 && is_string($decoded); $i++) {
            $next = json_decode($decoded, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                break;
            }

            $decoded = $next;
        }

        return is_array($decoded) ? $decoded : [];
    }
}

<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class ChinaRegions
{
    /** @return list<array{code: string, name: string}> */
    public static function provinces(): array
    {
        return Cache::remember('china_regions.provinces', 86400, function (): array {
            $path = storage_path('app/data/china-provinces.json');

            if (File::exists($path)) {
                $data = json_decode(File::get($path), true);

                if (is_array($data)) {
                    return array_map(fn (array $row): array => [
                        'code' => (string) ($row['code'] ?? ''),
                        'name' => (string) ($row['name'] ?? ''),
                    ], $data);
                }
            }

            return self::fallbackProvinces();
        });
    }

    /** @return list<array{code: string, name: string}> */
    public static function children(string $parentCode): array
    {
        $parentCode = trim($parentCode);

        if ($parentCode === '') {
            return [];
        }

        $isProvince = str_ends_with($parentCode, '0000');
        $file = $isProvince ? 'china-cities.json' : 'china-areas.json';
        $path = storage_path('app/data/'.$file);

        if (! File::exists($path)) {
            return [];
        }

        $cacheKey = 'china_regions.children.'.$parentCode;

        return Cache::remember($cacheKey, 86400, function () use ($path, $parentCode, $isProvince): array {
            $data = json_decode(File::get($path), true);

            if (! is_array($data)) {
                return [];
            }

            $provincePrefix = substr($parentCode, 0, 2);

            return collect($data)
                ->filter(function (array $row) use ($provincePrefix, $isProvince, $parentCode): bool {
                    $code = (string) ($row['code'] ?? '');
                    if ($code === '') {
                        return false;
                    }

                    if ($isProvince) {
                        if (isset($row['provinceCode'])) {
                            return (string) $row['provinceCode'] === $provincePrefix;
                        }

                        return str_starts_with($code, $provincePrefix) && strlen($code) <= 6;
                    }

                    $cityPrefix = substr($parentCode, 0, 4);

                    if (isset($row['cityCode'])) {
                        return (string) $row['cityCode'] === $cityPrefix;
                    }

                    return str_starts_with($code, $cityPrefix) && $code !== $parentCode;
                })
                ->map(fn (array $row): array => [
                    'code' => (string) $row['code'],
                    'name' => (string) ($row['name'] ?? ''),
                ])
                ->values()
                ->all();
        });
    }

    public static function label(string $code): ?string
    {
        foreach (self::provinces() as $row) {
            if ($row['code'] === $code) {
                return $row['name'];
            }
        }

        foreach (self::children(substr($code, 0, 2).'0000') as $row) {
            if ($row['code'] === $code) {
                return $row['name'];
            }
        }

        $province = substr($code, 0, 2).'0000';
        $cityPrefix = substr($code, 0, 4);
        foreach (self::children($province) as $city) {
            if (str_starts_with($code, substr($city['code'], 0, 4))) {
                foreach (self::children($city['code']) as $district) {
                    if ($district['code'] === $code) {
                        return $district['name'];
                    }
                }
            }
        }

        return null;
    }

    /** @return list<array{code: string, name: string}> */
    private static function fallbackProvinces(): array
    {
        return [
            ['code' => '110000', 'name' => '北京市'],
            ['code' => '120000', 'name' => '天津市'],
            ['code' => '130000', 'name' => '河北省'],
            ['code' => '140000', 'name' => '山西省'],
            ['code' => '150000', 'name' => '内蒙古自治区'],
            ['code' => '210000', 'name' => '辽宁省'],
            ['code' => '220000', 'name' => '吉林省'],
            ['code' => '230000', 'name' => '黑龙江省'],
            ['code' => '310000', 'name' => '上海市'],
            ['code' => '320000', 'name' => '江苏省'],
            ['code' => '330000', 'name' => '浙江省'],
            ['code' => '340000', 'name' => '安徽省'],
            ['code' => '350000', 'name' => '福建省'],
            ['code' => '360000', 'name' => '江西省'],
            ['code' => '370000', 'name' => '山东省'],
            ['code' => '410000', 'name' => '河南省'],
            ['code' => '420000', 'name' => '湖北省'],
            ['code' => '430000', 'name' => '湖南省'],
            ['code' => '440000', 'name' => '广东省'],
            ['code' => '450000', 'name' => '广西壮族自治区'],
            ['code' => '460000', 'name' => '海南省'],
            ['code' => '500000', 'name' => '重庆市'],
            ['code' => '510000', 'name' => '四川省'],
            ['code' => '520000', 'name' => '贵州省'],
            ['code' => '530000', 'name' => '云南省'],
            ['code' => '540000', 'name' => '西藏自治区'],
            ['code' => '610000', 'name' => '陕西省'],
            ['code' => '620000', 'name' => '甘肃省'],
            ['code' => '630000', 'name' => '青海省'],
            ['code' => '640000', 'name' => '宁夏回族自治区'],
            ['code' => '650000', 'name' => '新疆维吾尔自治区'],
            ['code' => '710000', 'name' => '台湾省'],
            ['code' => '810000', 'name' => '香港特别行政区'],
            ['code' => '820000', 'name' => '澳门特别行政区'],
        ];
    }
}

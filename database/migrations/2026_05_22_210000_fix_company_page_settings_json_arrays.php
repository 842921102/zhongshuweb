<?php

use App\Casts\JsonArrayCast;
use App\Models\CompanyPageSetting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        foreach (CompanyPageSetting::query()->cursor() as $setting) {
            $stations = JsonArrayCast::normalize($setting->getRawOriginal('service_stations'));

            if (self::isLegacyOfficeGroups($stations)) {
                $stations = CompanyPageSetting::defaultServiceStations();
            }

            $metrics = JsonArrayCast::normalize($setting->getRawOriginal('global_metrics'));

            $setting->forceFill([
                'service_stations' => $stations,
                'global_metrics' => $metrics,
            ])->save();
        }
    }

    public function down(): void
    {
        // 数据修复，无需回滚
    }

    /** @param  array<int|string, mixed>  $items */
    private static function isLegacyOfficeGroups(array $items): bool
    {
        $first = $items[0] ?? null;

        return is_array($first) && array_key_exists('count', $first);
    }
};

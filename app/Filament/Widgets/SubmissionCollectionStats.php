<?php

namespace App\Filament\Widgets;

use App\Services\AdminSubmissionStats;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SubmissionCollectionStats extends StatsOverviewWidget
{
    protected static ?int $sort = 0;

    protected ?string $heading = '前台数据收集';

    protected ?string $description = '官网表单与简历投递汇总，待处理项请尽快跟进';

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return count(AdminSubmissionStats::channels()) > 0;
    }

    protected function getStats(): array
    {
        $stats = [];
        $pendingTotal = AdminSubmissionStats::totalPending();
        $todayTotal = AdminSubmissionStats::totalToday();

        if ($pendingTotal > 0) {
            $stats[] = Stat::make('待处理合计', (string) $pendingTotal)
                ->description('全部渠道待跟进')
                ->descriptionIcon(Heroicon::OutlinedBellAlert)
                ->color('danger');
        }

        if ($todayTotal > 0) {
            $stats[] = Stat::make('今日新提交', (string) $todayTotal)
                ->description('今天 0 点至今')
                ->descriptionIcon(Heroicon::OutlinedCalendarDays)
                ->color('primary');
        }

        foreach (AdminSubmissionStats::channels() as $channel) {
            $description = "累计 {$channel['total']} 条";
            if ($channel['pending'] > 0) {
                $description .= " · 待处理 {$channel['pending']}";
            }
            if ($channel['today'] > 0) {
                $description .= " · 今日 +{$channel['today']}";
            }

            $stat = Stat::make($channel['label'], (string) $channel['pending'])
                ->description($description)
                ->color($channel['color']);

            if ($channel['url']) {
                $stat->url($channel['url']);
            }

            $stats[] = $stat;
        }

        return $stats;
    }
}

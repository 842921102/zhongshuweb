<?php

namespace App\Filament\Widgets;

use App\Services\AdminSubmissionStats;
use Filament\Widgets\Widget;

class RecentSubmissions extends Widget
{
    protected static ?int $sort = 1;

    protected string $view = 'filament.widgets.recent-submissions';

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return AdminSubmissionStats::recent(1)->isNotEmpty();
    }

    /**
     * @return \Illuminate\Support\Collection<int, array<string, mixed>>
     */
    public function getSubmissions()
    {
        return AdminSubmissionStats::recent(12);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getChannels(): array
    {
        return AdminSubmissionStats::channels();
    }
}

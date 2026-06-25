<?php

namespace App\Models\Concerns;

use App\Support\OverlayCopyStyle;

trait HasOverlayCopyColors
{
    public function overlayCopyStyle(): string
    {
        return OverlayCopyStyle::inline(
            $this->overlay_title_color ?? null,
            $this->overlay_subtitle_color ?? null,
        );
    }
}

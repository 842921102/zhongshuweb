<?php

namespace App\View\Composers;

use App\Services\SiteLayoutService;
use Illuminate\View\View;

class SiteLayoutComposer
{
    /** @var list<string> */
    private const SHARED_KEYS = [
        'locale',
        'navMenus',
        'productNavJson',
        'footer',
        'siteName',
        'siteDescription',
        'headerLogoDefault',
        'headerLogoScrolled',
    ];

    public function compose(View $view): void
    {
        $data = $view->getData();
        $missing = array_filter(self::SHARED_KEYS, fn (string $key): bool => ! array_key_exists($key, $data));

        if ($missing === []) {
            return;
        }

        $locale = $data['locale'] ?? current_lang();
        $shared = (new SiteLayoutService($locale))->shared();

        $view->with(array_intersect_key($shared, array_flip($missing)));
    }
}

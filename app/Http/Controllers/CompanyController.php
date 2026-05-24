<?php

namespace App\Http\Controllers;

use App\Services\CompanyPageService;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function index(): View
    {
        $locale = request()->query('lang', 'zh-cn');

        return view('company.index', (new CompanyPageService($locale))->data());
    }
}

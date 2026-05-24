<?php

namespace App\Http\Controllers;

use App\Services\HomePageService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $locale = $request->query('lang', 'zh-cn');
        $service = new HomePageService($locale);

        return view('home.official', $service->data());
    }
}

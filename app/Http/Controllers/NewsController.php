<?php

namespace App\Http\Controllers;

use App\Services\NewsPageService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(Request $request): View
    {
        $locale = $request->query('lang', 'zh-cn');

        return view('news.index', (new NewsPageService($locale))->indexData(
            categorySlug: $request->query('category'),
            perPage: (int) $request->query('per_page', NewsPageService::GRID_PER_PAGE) ?: NewsPageService::GRID_PER_PAGE,
        ));
    }

    public function show(Request $request, string $article): View
    {
        $locale = $request->query('lang', 'zh-cn');
        $data = (new NewsPageService($locale))->showData($article);

        if ($data === null) {
            abort(404);
        }

        return view('news.show', $data);
    }
}

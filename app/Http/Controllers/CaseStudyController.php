<?php

namespace App\Http\Controllers;

use App\Services\CaseStudyPageService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CaseStudyController extends Controller
{
    public function index(Request $request): View
    {
        $locale = current_lang();
        $service = new CaseStudyPageService($locale);

        return view('cases.index', $service->indexData(
            categorySlug: $request->query('category'),
            perPage: (int) $request->query('per_page', 12) ?: 12,
        ));
    }

    public function show(Request $request, string $slug): View
    {
        $locale = current_lang();
        $service = new CaseStudyPageService($locale);
        $data = $service->showData($slug);

        if ($data === null) {
            abort(404);
        }

        return view('cases.show', $data);
    }
}

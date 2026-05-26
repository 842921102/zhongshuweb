<?php

namespace App\Http\Controllers;

use App\Services\IndustrySolutionPageService;
use Illuminate\View\View;

class IndustrySolutionController extends Controller
{
    public function index(): View
    {
        $locale = current_lang();
        $service = new IndustrySolutionPageService($locale);

        return view('industry-cases.index', $service->indexData());
    }

    public function show(string $slug): View
    {
        $locale = current_lang();
        $service = new IndustrySolutionPageService($locale);
        $data = $service->showData($slug);

        if ($data === null) {
            abort(404);
        }

        return view('industry-cases.show', $data);
    }
}

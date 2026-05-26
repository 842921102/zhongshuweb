<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\CaseStudy;
use App\Models\Category;
use App\Models\IndustrySolution;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $locale = current_lang();
        $keyword = trim((string) $request->query('keyword', ''));
        $limit = max(1, min(20, (int) $request->query('limit', 8)));

        if ($keyword === '') {
            return response()->json([
                'code' => 1,
                'data' => ['items' => []],
            ]);
        }

        $like = '%'.$keyword.'%';
        $perType = max(2, (int) ceil($limit / 5));

        $items = collect()
            ->merge(
                Product::query()
                    ->forLocale($locale)
                    ->active()
                    ->where(function ($query) use ($like): void {
                        $query->where('name', 'like', $like)
                            ->orWhere('subtitle', 'like', $like)
                            ->orWhere('model_no', 'like', $like);
                    })
                    ->orderBy('sort_order')
                    ->limit($perType)
                    ->get()
                    ->map(fn (Product $product) => [
                        'title' => $product->name,
                        'type' => 'product',
                        'url' => $product->url(),
                    ])
            )
            ->merge(
                Category::query()
                    ->forLocale($locale)
                    ->active()
                    ->where('name', 'like', $like)
                    ->orderBy('sort_order')
                    ->limit($perType)
                    ->get()
                    ->map(fn (Category $category) => [
                        'title' => $category->name,
                        'type' => 'product_category',
                        'url' => localized_route('products.index', ['category' => $category->slug], $locale),
                    ])
            )
            ->merge(
                IndustrySolution::query()
                    ->forLocale($locale)
                    ->active()
                    ->published()
                    ->where(function ($query) use ($like): void {
                        $query->where('title', 'like', $like)
                            ->orWhere('summary', 'like', $like)
                            ->orWhere('excerpt', 'like', $like);
                    })
                    ->orderBy('sort_order')
                    ->limit($perType)
                    ->get()
                    ->map(fn (IndustrySolution $solution) => [
                        'title' => $solution->title,
                        'type' => 'industry',
                        'url' => $solution->url(),
                    ])
            )
            ->merge(
                CaseStudy::query()
                    ->forLocale($locale)
                    ->active()
                    ->published()
                    ->where(function ($query) use ($like): void {
                        $query->where('title', 'like', $like)
                            ->orWhere('summary', 'like', $like)
                            ->orWhere('excerpt', 'like', $like);
                    })
                    ->orderByDesc('published_at')
                    ->limit($perType)
                    ->get()
                    ->map(fn (CaseStudy $caseStudy) => [
                        'title' => $caseStudy->title,
                        'type' => 'case',
                        'url' => $caseStudy->url(),
                    ])
            )
            ->merge(
                Article::query()
                    ->forLocale($locale)
                    ->published()
                    ->where(function ($query) use ($like): void {
                        $query->where('title', 'like', $like)
                            ->orWhere('summary', 'like', $like);
                    })
                    ->orderByDesc('published_at')
                    ->limit($perType)
                    ->get()
                    ->map(fn (Article $article) => [
                        'title' => $article->title,
                        'type' => 'news',
                        'url' => $article->url(),
                    ])
            )
            ->unique(fn (array $item) => $item['type'].'|'.$item['url'])
            ->take($limit)
            ->values()
            ->all();

        return response()->json([
            'code' => 1,
            'data' => ['items' => $items],
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Banner;
use App\Models\CaseStudy;
use App\Models\Page;
use App\Models\Product;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function settings(): JsonResponse
    {
        return response()->json([
            'data' => SiteSetting::query()
                ->get()
                ->groupBy('group')
                ->map(fn ($items) => $items->pluck('value', 'key')),
        ]);
    }

    public function banners(Request $request): JsonResponse
    {
        $locale = $this->locale($request);
        $position = $request->string('position', 'home')->toString();

        return response()->json([
            'data' => Banner::query()
                ->forLocale($locale)
                ->active()
                ->where('position', $position)
                ->orderBy('sort_order')
                ->get(),
        ]);
    }

    public function pages(Request $request): JsonResponse
    {
        $locale = $this->locale($request);

        return response()->json([
            'data' => Page::query()
                ->forLocale($locale)
                ->published()
                ->orderBy('sort_order')
                ->get(['id', 'title', 'slug', 'subtitle', 'cover_image', 'published_at']),
        ]);
    }

    public function page(Request $request, string $slug): JsonResponse
    {
        $locale = $this->locale($request);

        $page = Page::query()
            ->forLocale($locale)
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json(['data' => $page]);
    }

    public function categories(Request $request): JsonResponse
    {
        $locale = $this->locale($request);

        return response()->json([
            'data' => ArticleCategory::query()
                ->forLocale($locale)
                ->active()
                ->orderBy('sort_order')
                ->get(['id', 'name', 'slug']),
        ]);
    }

    public function articles(Request $request): JsonResponse
    {
        $locale = $this->locale($request);
        $query = Article::query()
            ->forLocale($locale)
            ->published()
            ->with('category:id,name,slug')
            ->orderByDesc('published_at');

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->string('category')));
        }

        if ($request->boolean('featured')) {
            $query->featured();
        }

        return response()->json([
            'data' => $query->paginate($request->integer('per_page', 12)),
        ]);
    }

    public function article(Request $request, string $slug): JsonResponse
    {
        $locale = $this->locale($request);

        $article = Article::query()
            ->forLocale($locale)
            ->published()
            ->with('category:id,name,slug')
            ->where('slug', $slug)
            ->firstOrFail();

        $article->increment('views');

        return response()->json(['data' => $article]);
    }

    public function search(Request $request): JsonResponse
    {
        $locale = $this->locale($request);
        $keyword = trim((string) $request->query('keyword', ''));
        $limit = max(1, min(20, (int) $request->query('limit', 8)));

        if ($keyword === '') {
            return response()->json([
                'code' => 1,
                'data' => ['items' => []],
            ]);
        }

        $like = '%'.$keyword.'%';

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
                    ->limit($limit)
                    ->get()
                    ->map(fn (Product $product) => [
                        'title' => $product->name,
                        'type' => 'product',
                        'url' => $product->url(),
                    ])
            )
            ->merge(
                \App\Models\Category::query()
                    ->forLocale($locale)
                    ->active()
                    ->where('name', 'like', $like)
                    ->orderBy('sort_order')
                    ->limit($limit)
                    ->get()
                    ->map(fn (\App\Models\Category $category) => [
                        'title' => $category->name,
                        'type' => 'product_category',
                        'url' => localized_route('products.index', ['category' => $category->slug], $locale),
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
                    ->limit($limit)
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
                    ->limit($limit)
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

    private function locale(Request $request): string
    {
        return (string) $request->query('lang', $request->query('locale', 'zh-cn'));
    }
}

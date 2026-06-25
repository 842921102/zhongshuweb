<?php

namespace App\Services;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\NewsPageSetting;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class NewsPageService
{
    /** 列表网格每页条数：3 列 × 3 行（不含顶部主推） */
    public const GRID_PER_PAGE = 9;

    public function __construct(
        public string $locale = 'zh-cn',
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function indexData(?string $categorySlug = null, int $perPage = self::GRID_PER_PAGE): array
    {
        $settings = NewsPageSetting::forLocale($this->locale);

        $categories = ArticleCategory::query()
            ->forLocale($this->locale)
            ->active()
            ->orderBy('sort_order')
            ->get();

        $activeCategorySlug = 'all';
        $activeCategory = null;

        if ($categorySlug && $categorySlug !== 'all') {
            $activeCategory = $categories->firstWhere('slug', $categorySlug);
            if ($activeCategory) {
                $activeCategorySlug = $categorySlug;
            }
        }

        $featuredQuery = Article::query()
            ->forLocale($this->locale)
            ->published()
            ->orderByDesc('is_featured')
            ->orderByDesc('published_at')
            ->orderBy('sort_order')
            ->with('category');

        if ($activeCategory) {
            $featuredQuery->where('category_id', $activeCategory->id);
        }

        $featured = $featuredQuery->first();

        $listQuery = Article::query()
            ->forLocale($this->locale)
            ->published()
            ->with('category')
            ->orderByDesc('is_featured')
            ->orderByDesc('published_at')
            ->orderBy('sort_order');

        if ($activeCategory) {
            $listQuery->where('category_id', $activeCategory->id);
        }

        if ($featured) {
            $listQuery->whereKeyNot($featured->id);
        }

        /** @var LengthAwarePaginator<int, Article> $articles */
        $articles = $listQuery->paginate($perPage)->withQueryString();

        return array_merge((new SiteLayoutService($this->locale))->shared(), [
            'pageSettings' => $settings,
            'categories' => $categories,
            'activeCategorySlug' => $activeCategorySlug,
            'activeCategory' => $activeCategory,
            'featuredArticle' => $featured,
            'articles' => $articles,
            'readMoreLabel' => $settings->read_more_label ?: '阅读全文',
            'allCategoryLabel' => $settings->all_category_label ?: '全部',
        ]);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function showData(string $slugOrId): ?array
    {
        $article = Article::query()
            ->forLocale($this->locale)
            ->published()
            ->with('category')
            ->where(function ($q) use ($slugOrId): void {
                $q->where('slug', $slugOrId);
                if (is_numeric($slugOrId)) {
                    $q->orWhere('id', (int) $slugOrId);
                }
            })
            ->first();

        if (! $article) {
            return null;
        }

        $article->increment('views');
        $settings = NewsPageSetting::forLocale($this->locale);

        $related = Article::query()
            ->forLocale($this->locale)
            ->published()
            ->whereKeyNot($article->id)
            ->when(
                $article->category_id,
                fn ($q) => $q->where('category_id', $article->category_id)
            )
            ->orderByDesc('published_at')
            ->limit(3)
            ->with('category')
            ->get();

        if ($related->count() < 3) {
            $related = $this->mergeRelated($related, $article);
        }

        return array_merge((new SiteLayoutService($this->locale))->shared(), [
            'pageSettings' => $settings,
            'article' => $article,
            'relatedArticles' => $related,
            'readMoreLabel' => $settings->read_more_label ?: '阅读全文',
        ]);
    }

    /**
     * @param  Collection<int, Article>  $related
     * @return Collection<int, Article>
     */
    private function mergeRelated(Collection $related, Article $article): Collection
    {
        $extra = Article::query()
            ->forLocale($this->locale)
            ->published()
            ->whereKeyNot($article->id)
            ->whereNotIn('id', $related->pluck('id'))
            ->orderByDesc('published_at')
            ->limit(3 - $related->count())
            ->with('category')
            ->get();

        return $related->concat($extra);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Category;
use App\Models\HomeSection;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class SiteApiTest extends TestCase
{
    public function test_categories_endpoint_returns_article_categories_for_requested_locale(): void
    {
        ArticleCategory::query()->create([
            'name' => 'Company',
            'slug' => 'company',
            'locale' => 'en-us',
            'is_active' => true,
        ]);

        Category::query()->create([
            'name' => '产品分类',
            'slug' => 'product-category',
            'locale' => 'zh-cn',
            'is_active' => true,
        ]);

        $response = $this->getJson('/api/v1/categories?lang=en-us');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'company');
    }

    public function test_articles_endpoint_supports_featured_filter_without_error(): void
    {
        $category = ArticleCategory::query()->create([
            'name' => '新闻',
            'slug' => 'news',
            'locale' => 'zh-cn',
            'is_active' => true,
        ]);

        Article::query()->create([
            'title' => 'Featured article',
            'slug' => 'featured-article',
            'locale' => 'zh-cn',
            'category_id' => $category->id,
            'is_published' => true,
            'is_featured' => true,
            'published_at' => Carbon::now()->subDay(),
        ]);

        Article::query()->create([
            'title' => 'Regular article',
            'slug' => 'regular-article',
            'locale' => 'zh-cn',
            'category_id' => $category->id,
            'is_published' => true,
            'is_featured' => false,
            'published_at' => Carbon::now()->subDay(),
        ]);

        $response = $this->getJson('/api/v1/articles?featured=1');

        $response->assertOk()
            ->assertJsonCount(1, 'data.data')
            ->assertJsonPath('data.data.0.slug', 'featured-article');
    }

    public function test_home_sections_allow_same_section_key_in_multiple_locales(): void
    {
        HomeSection::query()->create([
            'section_key' => 'hero',
            'section_name' => '首页首屏',
            'locale' => 'zh-cn',
            'is_enabled' => true,
        ]);

        HomeSection::query()->create([
            'section_key' => 'hero',
            'section_name' => 'Hero',
            'locale' => 'en-us',
            'is_enabled' => true,
        ]);

        $this->assertSame(2, HomeSection::query()->where('section_key', 'hero')->count());
    }
}

<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\HomeSection;
use App\Models\Product;
use Tests\TestCase;

class SiteApiTest extends TestCase
{
    public function test_search_endpoint_returns_matching_products(): void
    {
        $category = Category::query()->create([
            'name' => '设备分类',
            'slug' => 'sweepers',
            'locale' => 'zh-cn',
            'is_active' => true,
        ]);

        Product::query()->create([
            'category_id' => $category->id,
            'name' => '智能清扫机器人',
            'slug' => 'smart-sweeper',
            'model_no' => 'ZS-SWEEP-01',
            'locale' => 'zh-cn',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this->getJson('/api/search?keyword=ZS-SWEEP');

        $response->assertOk()
            ->assertJsonPath('code', 1)
            ->assertJsonFragment([
                'title' => '智能清扫机器人',
                'type' => 'product',
            ])
            ->assertJsonCount(1, 'data.items');
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

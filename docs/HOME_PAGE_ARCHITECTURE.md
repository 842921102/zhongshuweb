# 众鼠科技首页 — 数据来源与改造说明

## 1. 各模块数据来源

| 前端模块 | HTML 结构 | 数据来源 | 查询条件 |
|---------|-----------|----------|----------|
| Banner | `section.hero` | `banners` | `position=home`, `is_active`, `locale`, 按 `sort_order` |
| 解决方案 | `section.solutions` | `categories`（一级） | `parent_id IS NULL`, `is_home_show=1`；`is_home_featured=1` → solutions-hero |
| 全系产品站 | `section.products` | `categories`（二级 Tab）+ `products` | Tab: `is_station_tab=1`；主推: `is_home_featured`；列表: 同分类其余 `is_home_show` |
| 项目案例 | `section.case-studies` | `case_studies` | 首页：`is_home_show`；列表页 `/cases` 见下表 |
| 合作伙伴 | `section.partners` | `site_partners` + `site_statistics` | 伙伴最多 12；指标 4 条 `is_home_show` |
| 新闻资讯 | `section.news` | `articles` | 优先 `is_home_show`，否则最新 3 条已发布 |
| 关于我们 | `section.about` | `company_page_settings` | `intro_*`、`hero_media_url` |
| Footer | `footer.site-footer` | `site_settings` + `site_footer_links` | 键值配置 + 分组链接 |
| 顶部导航 | `header.site-header__nav` | `site_nav_menus` | 顶级项 `parent_id IS NULL`；`product_mega` 类型联动产品分类下拉 |
| 模块标题/开关 | `section-heading` | `home_sections` | `section_key` 对应 hero/solutions/products… |

控制器：`HomeController` → `HomePageService::data()` → 视图 `home.official` + `home.partials.*`

## 2. 复用的现有表

| 需求表名 | 实际复用 | 说明 |
|---------|----------|------|
| site_banners | **banners** | 已扩展 `image_mobile`, `locale` |
| product_categories | **categories** | 已扩展 `parent_id`, 封面/图标/首页标记等 |
| news_articles | **articles** | 已扩展 `is_home_show`, `sort_order`, `locale` |
| site_settings | **site_settings** | 原有键值表，存站点/页脚/Logo |

## 3. 已新增字段（迁移 `2026_05_22_120000_extend_homepage_cms_schema`）

- **banners**: `image_mobile`, `locale`
- **categories**: `parent_id`, `subtitle`, `description`, `icon`, `cover_image`, `link`, `is_home_show`, `is_home_featured`, `is_station_tab`, `locale`
- **articles**: `is_home_show`, `sort_order`, `locale`
- **categories / products / articles**（`2026_05_26_100000_add_responsive_cover_images`）: `cover_image_mobile`；产品另增 `home_image_mobile`
- **case_studies / join_page_settings / company_page_settings / support_videos / products**（`2026_05_26_120000_add_responsive_media_extended`）: 各模块 `*_mobile` 封面/背景字段

## 4. 新增表

- **products** — 产品列表
- **case_studies** — 案例
- **site_partners** — 合作伙伴
- **site_statistics** — 首页数据指标
- **home_sections** — 首页模块配置
- **site_footer_links** — 页脚分组链接
- **site_nav_menus** — 顶部导航菜单（迁移 `2026_05_22_160000_create_site_nav_menus_table`）
- **case_study_categories** / **case_page_settings** — 客户案例列表页（迁移 `2026_05_22_170000_extend_case_studies_cms`）

### 客户案例列表页 `/cases`

| 模块 | 数据表 | 说明 |
|------|--------|------|
| 页头标题/引言/SEO | `case_page_settings` | 后台「案例页设置」 |
| 顶栏精选轮播 | `case_studies` | `is_featured=1` |
| 分类 Tab | `case_study_categories` | `?category=slug` |
| 案例网格 + 分页 | `case_studies` | `is_active`，`published_at` |
| 详情页 | `case_studies` | `/cases/{slug}`，`content` 富文本 |

后台：**案例管理** → 案例列表、案例分类、案例页设置

### site_nav_menus 字段说明

| 字段 | 说明 |
|------|------|
| `menu_key` | 系统标识（home、product_mega 等），同语言唯一 |
| `menu_type` | `link` 普通链接；`product_mega` 产品 mega 下拉（内容来自 categories） |
| `label` | 前台显示名称 |
| `url` | 链接：锚点 `#home-case`、路径 `/`、外链 |
| `route_keys` | `data-route`，导航高亮 |
| `search_keywords` | `data-search`，顶部站内搜索 |
| `parent_id` | 预留二级菜单（当前前台仅渲染顶级） |

> 旧版 **homepages** 单表配置已移除，首页统一走分表 CMS（`banners`、`categories`、`home_sections` 等）。

## 5. 后台菜单

| 菜单组 | 资源 |
|--------|------|
| 官网管理 | 轮播图、首页模块、合作伙伴、数据指标、**菜单管理** |
| 产品管理 | 产品分类、产品列表 |
| 案例管理 | 案例列表 |
| 新闻管理 | 新闻资讯 |
| 关于我们 | 公司介绍页设置 |
| 系统设置 | 站点配置、页脚链接 |

## 6. 前端模板读取方式

```
layouts/home.blade.php          # 引入 home-assets CSS + home-fullpage.css
home/official.blade.php         # 按 home_sections 开关 include 各屏
home/partials/header.blade.php  # Logo/导航/搜索/语言 + productNavJson
home/partials/hero.blade.php    # @foreach($banners) data-banner-pc/mobile
home/partials/solutions.blade.php
home/partials/products.blade.php
home/partials/cases.blade.php
home/partials/partners.blade.php
home/partials/news.blade.php
home/partials/about.blade.php
home/partials/footer.blade.php
```

图片 URL：`media_url($path, $fallback)` — 支持 `/home-assets/`、storage、外链。

响应式图片（≤968px 与 `site-layout.js` 一致）：

- 组件：`<x-responsive-image pc="" mobile="" fallback="" />`、`<x-responsive-bg />`（背景图）
- 逻辑：`App\Support\ResponsiveMedia` / `responsive_media()`；有独立手机图时用原生 `<picture><source media="(max-width: 968px)">`
- 后台：分类 / 产品 / 文章 / 案例 / 招聘 / 关于我们 / 支持视频 / 轮播图 等可分别上传 PC 图与手机图；手机图留空则自动用 PC 图
- 文字：`public/css/site-typography.css`（仅 max-width）统一缩小标题、正文、按钮与 `.cms-rich-content` 富文本

## 7. 禁止写死的内容

- 轮播图、分类、产品、案例、伙伴 Logo、统计数字、新闻列表、关于文案、页脚联系方式与链接
- 各模块标题/副标题（来自 `home_sections`，无配置时用 partial 默认值）
- 导航产品下拉 JSON（`HomePageService::productNavJson()`）

可保留为静态资源：SVG 图标、`home.js` / `site-layout.js`、空列表 CSS（`products__list:empty`）。

## 8. 分屏与响应式

- 保留官网 `home-common.css` / `home.css` 与原有 class（hero、products__tab、case-card 等）— **PC（>1024px）默认不变**
- 叠加 `public/css/home-fullpage.css`：各 `section.screen-section` 使用 `min-height: 100vh`（仅首页）
- 全站响应式覆盖：`public/css/site-responsive.css`，在 `layouts/home.blade.php` 中位于各页 `@stack('head')` **之后**加载，仅含 `@media (max-width: …)` 规则
- 断点：1024 平板 / 768 手机 / 640 小屏；顶栏与 Banner 图切换 JS 仍为 **968px**（`site-layout.js`）

## 本地预览

```bash
php artisan migrate --seed
php artisan serve
# 访问 http://127.0.0.1:8000/
# 后台 http://127.0.0.1:8000/admin
```

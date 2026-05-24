# 官网 CMS（Laravel + Filament）

基于 **Laravel 13** 与 **Filament 4** 的企业官网内容管理系统，包含后台管理与供前端调用的 REST API。

## 功能模块

| 模块 | 说明 |
|------|------|
| **首页模块** | 轮播图、首页模块开关/标题、产品分类、产品、案例、合作伙伴、数据指标、新闻等 |
| 单页内容 | 关于我们、服务介绍等静态页面 |
| 新闻动态 | 文章发布、分类、推荐、SEO（首页新闻区自动读取） |
| 文章分类 | 新闻栏目分类 |
| 轮播图 | 首页/各区块 Banner，支持定时展示 |
| 站点设置 | 网站名称、Logo、联系方式、备案号等 |

## 环境要求

- PHP >= 8.2
- Composer
- SQLite（默认）或 MySQL

## 快速开始

```bash
# 安装依赖（已完成可跳过）
composer install

# 配置环境
cp .env.example .env
php artisan key:generate

# 数据库迁移与初始数据
php artisan migrate
php artisan db:seed

# 上传文件存储链接
php artisan storage:link

# 启动开发服务器
php artisan serve
```

## 后台登录

- 地址：<http://127.0.0.1:8000/admin>
- **官网管理**：轮播图、首页模块、合作伙伴、数据指标等
- **产品/案例/新闻**：对应菜单维护各区块内容
- **官网前台**：<http://127.0.0.1:8000/>
- 默认账号：`admin@example.com`
- 默认密码：`password`

> 生产环境请修改密码：`php artisan make:filament-user`

## 公开 API（供官网前端调用）

| 方法 | 路径 | 说明 |
|------|------|------|
| GET | `/api/v1/settings` | 站点配置（按分组） |
| GET | `/api/v1/banners?position=home` | 轮播图 |
| GET | `/api/v1/pages` | 已发布页面列表 |
| GET | `/api/v1/pages/{slug}` | 单页详情 |
| GET | `/api/v1/categories` | 文章分类 |
| GET | `/api/v1/articles` | 文章列表（支持 `category`、`featured`、`page`） |
| GET | `/api/v1/articles/{slug}` | 文章详情 |

### 读取站点配置（PHP）

```php
use App\Models\SiteSetting;

$siteName = SiteSetting::get('site_name', '默认名称');
```

## 目录结构

```
app/
├── Filament/Resources/   # Filament 后台资源
├── Http/Controllers/Api/ # 官网 API
└── Models/               # 数据模型
routes/
├── api.php               # API 路由
└── web.php
```

## 下一步建议

1. 使用 Vue / React / Next.js 等搭建官网前台，对接上述 API
2. 将 `.env` 中 `DB_CONNECTION` 改为 `mysql` 用于生产环境
3. 配置 Nginx + PHP-FPM 部署

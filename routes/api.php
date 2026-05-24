<?php

use App\Http\Controllers\Api\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/search', [SiteController::class, 'search'])->name('api.search');

Route::prefix('v1')->group(function (): void {
    Route::get('/settings', [SiteController::class, 'settings']);
    Route::get('/banners', [SiteController::class, 'banners']);
    Route::get('/pages', [SiteController::class, 'pages']);
    Route::get('/pages/{slug}', [SiteController::class, 'page']);
    Route::get('/categories', [SiteController::class, 'categories']);
    Route::get('/articles', [SiteController::class, 'articles']);
    Route::get('/articles/{slug}', [SiteController::class, 'article']);
});

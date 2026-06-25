<?php

use App\Http\Controllers\Api\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/search', [SiteController::class, 'search'])->name('api.search');
Route::get('/products/catalog', [\App\Http\Controllers\ProductController::class, 'catalog'])->name('api.products.catalog');

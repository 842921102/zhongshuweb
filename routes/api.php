<?php

use App\Http\Controllers\Api\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/search', [SiteController::class, 'search'])->name('api.search');

<?php

use App\Http\Controllers\CaseStudyController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JoinUsController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/about', [CompanyController::class, 'index'])->name('about.index');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::post('/products/{product}/consult', [ProductController::class, 'consult'])->name('products.consult');

Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{article}', [NewsController::class, 'show'])->name('news.show');

Route::get('/cases', [CaseStudyController::class, 'index'])->name('cases.index');
Route::get('/cases/{slug}', [CaseStudyController::class, 'show'])->name('cases.show');

Route::get('/join-us', [JoinUsController::class, 'index'])->name('joinus.index');
Route::post('/join-us/apply', [JoinUsController::class, 'apply'])->name('joinus.apply');

Route::get('/support', [SupportController::class, 'index'])->name('support.index');
Route::get('/support/region', [SupportController::class, 'region'])->name('support.region');
Route::post('/support/submit', [SupportController::class, 'submit'])->name('support.submit');
Route::post('/support/videos/{video}/play', [SupportController::class, 'videoPlay'])->name('support.video.play');

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\LevelCategoryController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactController;

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth:admin')->group(static function () {
    // TOP
    Route::get('top', static function () {
        return view('admin.top');
    })->name('top');

    // 会員管理
    Route::resource('users', UserController::class)->only(['index', 'show']);

    // レビュー管理
    Route::resource('reviews', ReviewController::class)->only(['index', 'show', 'destroy']);

    // レベルカテゴリ管理
    Route::resource('level_categories', LevelCategoryController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

    // 技術カテゴリ管理
    Route::resource('categories', CategoryController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

    // お問合せ管理
    Route::resource('contacts', ContactController::class)->only('index', 'show');
    Route::get('/contacts/{contact}/update_status', [ContactController::class, 'updateStatus'])->name('contacts.update_status');
});

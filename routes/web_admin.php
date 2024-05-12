<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\UserController;

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

});

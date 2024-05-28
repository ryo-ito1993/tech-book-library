<?php

use App\Http\Controllers\User\TopController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TopController::class, 'index'])->name('user.top');

Auth::routes();

// ゲストログイン
Route::get('guest', [LoginController::class, 'guestLogin'])->name('login.guest');

Route::redirect('/home', '/');

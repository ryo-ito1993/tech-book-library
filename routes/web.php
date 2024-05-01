<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\TopController;

Route::get('/', [TopController::class, 'index'])->name('user.top');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

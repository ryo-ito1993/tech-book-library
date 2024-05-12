<?php

use App\Http\Controllers\User\TopController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TopController::class, 'index'])->name('user.top');

Auth::routes();

Route::redirect('/home', '/');

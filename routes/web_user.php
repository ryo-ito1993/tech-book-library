<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\LibraryController;
use App\Http\Controllers\User\FavoriteBookController;
use App\Http\Controllers\User\ReviewController;
use App\Http\Controllers\User\BookController;

Route::get('/library', [LibraryController::class, 'create'])->name('library.create');
Route::post('/library', [LibraryController::class, 'store'])->name('library.store');
Route::get('/favorite_books', [FavoriteBookController::class, 'index'])->name('favorite_books.index');
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::get('/reviews/create/{isbn}', [ReviewController::class, 'create'])->name('reviews.create');
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
Route::get('/books/seach', [BookController::class, 'search'])->name('books.search');
Route::get('/books/{isbn}', [BookController::class, 'show'])->name('books.show');

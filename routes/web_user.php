<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\LibraryController;
use App\Http\Controllers\User\FavoriteBookController;
use App\Http\Controllers\User\ReviewController;
use App\Http\Controllers\User\BookController;

Route::controller(ReviewController::class)->name('reviews.')->group(static function () {
    Route::get('/reviews', 'index')->name('index');
});

Route::middleware(['auth:web', 'verified'])->group(static function () {
    Route::controller(BookController::class)->name('books.')->group(static function () {
        Route::get('/books/search', 'search')->name('search');
        Route::get('/books/{isbn}', 'show')->name('show');
    });

    Route::controller(LibraryController::class)->name('library.')->group(static function () {
        Route::get('/library', 'create')->name('create');
        Route::post('/library', 'store')->name('store');
    });

    Route::get('/favorite_books', [FavoriteBookController::class, 'index'])->name('favorite_books.index');

    Route::controller(ReviewController::class)->name('reviews.')->group(static function () {
        Route::get('/reviews/create/{isbn}', 'create')->name('create');
        Route::get('/reviews/edit/{review}', 'edit')->name('edit');
        Route::put('/reviews/{review}', 'update')->name('update');
        Route::post('/reviews', 'store')->name('store');
        Route::delete('/reviews/{review}', 'destroy')->name('destroy');
    });
});

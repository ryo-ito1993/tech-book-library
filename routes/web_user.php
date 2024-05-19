<?php

use App\Http\Controllers\User\BookController;
use App\Http\Controllers\User\FavoriteBookController;
use App\Http\Controllers\User\LibraryController;
use App\Http\Controllers\User\ReviewController;
use App\Http\Controllers\User\ContactController;
use Illuminate\Support\Facades\Route;

// レビュー一覧
Route::controller(ReviewController::class)->name('reviews.')->group(static function () {
    Route::get('/reviews', 'index')->name('index');
});

// お問い合わせ
Route::prefix('contacts')->name('contacts.')->group(static function () {
    Route::post('confirm', [ContactController::class, 'confirm'])->name('confirm');
    Route::get('complete', [ContactController::class, 'complete'])->name('complete');
    Route::post('back', [ContactController::class, 'back'])->name('back');
});
Route::resource('contacts', ContactController::class)->only('create', 'store');

// ログイン済のユーザーのみアクセス可能
Route::middleware(['auth:web', 'verified'])->group(static function () {
    // 本の検索・詳細
    Route::controller(BookController::class)->name('books.')->group(static function () {
        Route::get('/books/search', 'search')->name('search');
        Route::get('/books/{isbn}', 'show')->name('show');
    });

    // 図書館の登録
    Route::controller(LibraryController::class)->name('library.')->group(static function () {
        Route::get('/library', 'create')->name('create');
        Route::post('/library', 'store')->name('store');
        Route::delete('/library/{library}', 'destroy')->name('destroy');
    });

    // 本のお気に入り
    Route::get('/favorite_books', [FavoriteBookController::class, 'index'])->name('favorite_books.index');

    // レビューの登録・編集・削除
    Route::controller(ReviewController::class)->name('reviews.')->group(static function () {
        Route::get('/reviews/create/{isbn}', 'create')->name('create');
        Route::get('/reviews/edit/{review}', 'edit')->name('edit');
        Route::put('/reviews/{review}', 'update')->name('update');
        Route::post('/reviews', 'store')->name('store');
        Route::delete('/reviews/{review}', 'destroy')->name('destroy');
    });
});

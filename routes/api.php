<?php

use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\LibraryController;
use Illuminate\Support\Facades\Route;

Route::controller(LibraryController::class)->name('libraries.')->group(static function () {
    Route::post('/getLibrariesByLocation', 'getLibrariesByLocation')->name('getLibrariesByLocation');
    Route::post('/getLibrariesByPrefCity', 'getLibrariesByPrefCity')->name('getLibrariesByPrefCity');
    Route::get('/getCitiesByPrefecture/{prefectureId}', 'getCitiesByPrefecture')->name('getCitiesByPrefecture');
    Route::post('/getBookAvailability', 'getBookAvailability')->name('getBookAvailability');

});
Route::post('/books/toggleFavorite', [BookController::class, 'toggleFavorite'])->name('books.toggleFavorite');
Route::post('/books/toggleRead', [BookController::class, 'toggleRead'])->name('books.toggleRead');

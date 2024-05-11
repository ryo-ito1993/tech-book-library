<?php

use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\LibraryController;
use Illuminate\Support\Facades\Route;

Route::controller(LibraryController::class)->group(function () {
    Route::post('/getLibrariesByLocation', 'getLibrariesByLocation');
    Route::post('/getLibrariesByPrefCity', 'getLibrariesByPrefCity');
    Route::get('/getCitiesByPrefecture/{prefectureId}', 'getCitiesByPrefecture');
    Route::post('/getBookAvailability', 'getBookAvailability');

});
Route::post('/books/toggleFavorite', [BookController::class, 'toggleFavorite']);

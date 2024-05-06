<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\LibraryController;
use App\Http\Controllers\API\BookController;

Route::post('/getLibrariesByLocation', [LibraryController::class, 'getLibrariesByLocation']);
Route::post('/getLibrariesByPrefCity', [LibraryController::class, 'getLibrariesByPrefCity']);
Route::get('/getCitiesByPrefecture/{prefectureId}', [LibraryController::class, 'getCitiesByPrefecture']);

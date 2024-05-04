<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\LibraryController;

Route::post('/libraries', [LibraryController::class, 'getLibraries']);

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FavoriteBookController extends Controller
{
    public function index()
    {
        return view('user.favorite_books.index');
    }
}

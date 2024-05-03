<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function search(): View
    {
        return view('user.books.search');
    }

    public function show(): View
    {
        return view('user.books.show');
    }
}

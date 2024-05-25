<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\View\View;

class ReadBookController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $readBookIds = $user->readBooks()->pluck('book_id');
        $books = Book::whereIn('id', $readBookIds)->with('authors')->latest()->paginate(12);

        return view('user.favorite_books.index', ['books' => $books, 'user' => $user]);
    }
}

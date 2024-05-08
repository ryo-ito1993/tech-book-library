<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Library\googleBooksApiLibrary;
use App\Models\Book;

class FavoriteBookController extends Controller
{
    public function __construct(
        protected googleBooksApiLibrary $googleBooksApiLibrary
    ) {
    }

    public function index(): View
    {
        $user = auth()->user();
        $favoriteBookIds = $user->favoriteBooks()->pluck('book_id');
        $books = Book::whereIn('id', $favoriteBookIds)->get();

        return view('user.favorite_books.index', ['books' => $books]);
    }
}

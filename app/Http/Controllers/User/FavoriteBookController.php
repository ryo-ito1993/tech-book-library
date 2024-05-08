<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Library\googleBooksApiLibrary;

class FavoriteBookController extends Controller
{
    public function __construct(
        protected googleBooksApiLibrary $googleBooksApiLibrary
    ) {
    }

    public function index(): View
    {
        $user = auth()->user();
        $favoriteBooks = $user->favoriteBooks;
        $favoriteBooksIsbn = $favoriteBooks->pluck('isbn');
        $books = [];

        if ($favoriteBooksIsbn->isNotEmpty()) {
            foreach ($favoriteBooksIsbn as $isbn)
            {
                $books[] = $this->googleBooksApiLibrary->getBookByIsbn($isbn);
            }
        }
        return view('user.favorite_books.index', ['books' => $books]);
    }
}

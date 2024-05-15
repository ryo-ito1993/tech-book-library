<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\View\View;

class FavoriteBookController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $favoriteBookIds = $user->favoriteBooks()->pluck('book_id');
        $books = Book::whereIn('id', $favoriteBookIds)->with('authors')->latest()->paginate(20);

        return view('user.favorite_books.index', ['books' => $books]);
    }
}

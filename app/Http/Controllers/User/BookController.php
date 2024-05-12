<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Library\calilApiLibrary;
use App\Library\googleBooksApiLibrary;
use App\Models\Book;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function __construct(
        protected CalilApiLibrary $calilApiLibrary,
        protected googleBooksApiLibrary $googleBooksApiLibrary
    ) {
    }

    public function search(Request $request): View
    {
        $title = $request->input('title', '');
        $author = $request->input('author', '');
        $isbn = $request->input('isbn', '');

        $query = '';

        if ($title) {
            $query .= 'intitle:' . $title . '+';
        }

        if ($author) {
            $query .= 'inauthor:' . $author . '+';
        }

        if ($isbn) {
            $query .= 'isbn:' . $isbn . '+';
        }

        $query = rtrim($query, '+');

        $hasSearched = $query !== '';

        $books = [];

        if ($query) {
            $books = $this->googleBooksApiLibrary->searchBooks($query);
        }

        return view('user.books.search', ['books' => $books, 'title' => $title, 'author' => $author, 'isbn' => $isbn, 'hasSearched' => $hasSearched]);
    }

    public function show(string $isbn): View
    {
        $user = auth()->user();
        $book = $this->googleBooksApiLibrary->getBookByIsbn($isbn);

        $bookModel = Book::where('isbn', $isbn)->first();
        $isFavorite = $bookModel ? $user->favoriteBooks()->where('book_id', $bookModel->id)->exists() : false;
        $reviews = $bookModel ? $bookModel->reviews->sortByDesc('created_at') : [];

        return view('user.books.show', ['user' => $user, 'book' => $book, 'isFavorite' => $isFavorite, 'reviews' => $reviews]);
    }
}

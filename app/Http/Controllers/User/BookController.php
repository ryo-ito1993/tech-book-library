<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Library\googleBooksApiLibrary;
use App\Services\BookService;
use App\Models\Book;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function __construct(
        protected googleBooksApiLibrary $googleBooksApiLibrary,
        protected BookService $bookService,
    ) {
    }

    public function search(Request $request): View
    {
        $searchParams = [
            'title' => $request->input('title', ''),
            'author' => $request->input('author', ''),
            'isbn' => $request->input('isbn', '')
        ];

        $books = $this->bookService->searchBooks($searchParams);

        $hasSearched = !empty(array_filter($searchParams));

        return view('user.books.search', ['books' => $books, 'title' => $searchParams['title'], 'author' => $searchParams['author'], 'isbn' => $searchParams['isbn'], 'hasSearched' => $hasSearched]);
    }

    public function show(string $isbn): View
    {
        $user = auth()->user();
        $book = $this->googleBooksApiLibrary->getBookByIsbn($isbn);

        $bookModel = Book::with(['reviews.user', 'reviews.categories', 'reviews.levelCategories'])->where('isbn', $isbn)->first();
        $isFavorite = $bookModel ? $user->favoriteBooks()->where('book_id', $bookModel->id)->exists() : false;
        $isRead = $bookModel ? $user->readBooks()->where('book_id', $bookModel->id)->exists() : false;
        $reviews = $bookModel ? $bookModel->reviews->sortByDesc('created_at') : [];

        return view('user.books.show', ['user' => $user, 'book' => $book, 'isFavorite' => $isFavorite, 'isRead' => $isRead, 'reviews' => $reviews]);
    }
}

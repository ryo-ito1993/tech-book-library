<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Library\GoogleBooksApiLibrary;
use App\Library\CalilApiLibrary;

class BookController extends Controller
{
    protected $googleBooksApiLibrary;
    protected $calilApiLibrary;

    public function __construct(GoogleBooksApiLibrary $googleBooksApiLibrary, CalilApiLibrary $calilApiLibrary)
    {
        $this->googleBooksApiLibrary = $googleBooksApiLibrary;
        $this->calilApiLibrary = $calilApiLibrary;
    }

    public function search(Request $request)
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

        $books= [];

        if ($query) {
            $books = $this->googleBooksApiLibrary->searchBooks($query, 30);
        }

        return view('user.books.search', ['books' => $books, 'title' => $title, 'author' => $author, 'isbn' => $isbn, 'hasSearched' => $hasSearched]);
    }

    public function show($isbn): View
    {
        $user = auth()->user();
        $systemid = $user->library->system_id;
        $book = $this->googleBooksApiLibrary->getBookByIsbn($isbn);
        $bookAvailable = $this->calilApiLibrary->checkBookAvailability($isbn, $systemid);
        return view('user.books.show', ['user' => $user, 'book' => $book, 'bookAvailable' => $bookAvailable['books'][$isbn][$systemid] ?? null]);
    }
}

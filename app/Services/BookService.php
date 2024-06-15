<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Database\Eloquent\Builder;
use App\Library\googleBooksApiLibrary;

class BookService
{
    public function __construct(
        protected googleBooksApiLibrary $googleBooksApiLibrary
    ) {
    }

    // 検索
    public function searchBooks(array $searchParams): array
    {
        $title = $searchParams['title'] ?? '';
        $author = $searchParams['author'] ?? '';
        $isbn = $searchParams['isbn'] ?? '';

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

        if ($query) {
            return $this->googleBooksApiLibrary->searchBooks($query);
        }

        return [];
    }

    // お気に入り、読書、レビューの関連チェック
    public function hasNoRelations(Book $book): bool
    {
        return !$book->favorites()->exists() && !$book->reads()->exists() && !$book->reviews()->exists();
    }
}

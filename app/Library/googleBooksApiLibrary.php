<?php

namespace App\Library;

use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\HttpException;

class googleBooksApiLibrary
{
    protected $apiUrl = 'https://www.googleapis.com/books/v1/volumes';

    public function searchBooks($query, $maxResults = 30)
    {
        $response = Http::get($this->apiUrl, [
            'q' => $query,
            'maxResults' => $maxResults,
        ]);

        if ($response->ok()) {
            $books = collect($response->json('items'))
                ->map(function ($item) {
                    $volumeInfo = $item['volumeInfo'] ?? [];
                    $imageLinks = $volumeInfo['imageLinks'] ?? [];
                    $industryIdentifiers = $volumeInfo['industryIdentifiers'] ?? [];
                    $isbn = collect($industryIdentifiers)
                        ->filter(function ($identifier) {
                            return \in_array($identifier['type'], ['ISBN_13', 'ISBN_10'], true);
                        })
                        ->pluck('identifier')
                        ->first() ?? 'ISBN不明';

                    if ($isbn === 'ISBN不明') {
                        return null;
                    }

                    return [
                        'title' => $volumeInfo['title'] ?? '',
                        'authors' => $volumeInfo['authors'] ?? [],
                        'publishedDate' => $volumeInfo['publishedDate'] ?? '',
                        'description' => $volumeInfo['description'] ?? '',
                        'isbn' => $isbn,
                        'thumbnail' => $imageLinks['thumbnail'] ?? '',
                    ];
                })
                ->filter()
                ->all();

            return $books;
        }  
            $status = $response->status();
            $error = $response->body();
            throw new HttpException($status, "API request failed: " . $error);
        
    }

    public function getBookByIsbn($isbn)
    {
        $query = 'isbn:' . $isbn;
        $books = $this->searchBooks($query, 1);
        return $books[0] ?? null;
    }
}

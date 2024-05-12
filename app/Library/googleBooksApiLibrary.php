<?php

namespace App\Library;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class googleBooksApiLibrary
{
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('services.google_books.api_base_url');
    }

    public function searchBooks(string $query, int $maxResults = 40): array
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
                        'publisher' => $volumeInfo['publisher'] ?? '',
                        'isbn' => $isbn,
                        'thumbnail' => $imageLinks['thumbnail'] ?? '',
                        'infoLink' => $volumeInfo['infoLink'] ?? '',
                    ];
                })
                ->filter()
                ->all();

            return $books;
        }
        $status = $response->status();
        $error = $response->body();
        Log::error('API Request Failed', [
            'status_code' => $status,
            'error' => $error,
        ]);
        throw new HttpException($status, 'API request failed: ' . $error);
    }

    public function getBookByIsbn(string $isbn): ?array
    {
        $query = 'isbn:' . $isbn;
        $books = $this->searchBooks($query, 1);

        return $books[0] ?? null;
    }
}

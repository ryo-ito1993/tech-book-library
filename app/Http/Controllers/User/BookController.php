<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BookController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query', '');

        if ($query) {
            $response = Http::get('https://www.googleapis.com/books/v1/volumes', [
                'q' => $query,
                'maxResults' => 30,
            ]);

            $books = collect($response->json('items'))
                ->map(function ($item) {
                    $volumeInfo = $item['volumeInfo'] ?? [];
                    $imageLinks = $volumeInfo['imageLinks'] ?? [];
                    $industryIdentifiers = $volumeInfo['industryIdentifiers'] ?? [];
                    // Find either ISBN_13 or ISBN_10
                    $isbn = collect($industryIdentifiers)
                        ->filter(function ($identifier) {
                            return in_array($identifier['type'], ['ISBN_13', 'ISBN_10']);
                        })
                        ->pluck('identifier')
                        ->first() ?? 'ISBN不明';

                    if ($isbn === 'ISBN不明') {
                        return null;
                    }

                    return [
                        'id' => $item['id'],
                        'title' => $volumeInfo['title'] ?? 'タイトル不明',
                        'authors' => $volumeInfo['authors'] ?? ['著者不明'],
                        'thumbnail' => $imageLinks['thumbnail'] ?? '',
                        'isbn' => $isbn,
                    ];
                })
                ->filter()
                ->all();
        } else {
            $books = [];
        }

        return view('user.books.search', ['books' => $books, 'query' => $query]);
    }

    public function show(): View
    {
        return view('user.books.show');
    }
}

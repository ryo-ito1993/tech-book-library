<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\FavoriteBook;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    public function toggleFavorite(Request $request)
    {
        $user = User::findorFail($request->input('user_id'));
        $isbn = $request->input('isbn');

        $book = Book::firstOrCreate(
            ['isbn' => $isbn],
            ['title' => $request->input('title'), 'thumbnail' => $request->input('thumbnail')]
        );
        if ($book->authors()->doesntExist()) {
            $authors = $request->input('authors', []);
            foreach ($authors as $author) {
                $book->authors()->firstOrCreate(['name' => $author]);
            }
        }

        $favorite = $user->favoriteBooks()->where('book_id', $book->id)->first();

        if ($favorite) {
            $favorite->delete();
            if (FavoriteBook::where('book_id', $book->id)->doesntExist()) {
                $book->delete();
            }
        } else {
            $user->favoriteBooks()->create(['book_id' => $book->id]);
        }

        return response()->json(['status' => 'success']);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\FavoriteBook;
use App\Models\ReadBook;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookController extends Controller
{
    public function toggleFavorite(Request $request): JsonResponse
    {
        $user = User::findorFail($request->input('user_id'));
        $isbn = $request->input('isbn');

        \DB::transaction(static function () use ($user, $isbn, $request) {
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

            $favorite = FavoriteBook::where('user_id', $user->id)->where('book_id', $book->id)->first();
            $read = ReadBook::where('user_id', $user->id)->where('book_id', $book->id)->first();

            if ($favorite) {
                $favorite->delete();
                if ($book->favorites()->doesntExist() && $book->reads()->doesntExist() && $book->reviews()->doesntExist()) {
                    $book->delete();
                }
            } else {
                $user->favoriteBooks()->attach($book->id, [
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                if ($read) {
                    $read->delete();
                }
            }
        });

        return response()->json(['status' => 'success']);
    }

    public function toggleRead(Request $request): JsonResponse
    {
        $user = User::findorFail($request->input('user_id'));
        $isbn = $request->input('isbn');

        \DB::transaction(static function () use ($user, $isbn, $request) {
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

            $read = ReadBook::where('user_id', $user->id)->where('book_id', $book->id)->first();
            $favorite = FavoriteBook::where('user_id', $user->id)->where('book_id', $book->id)->first();

            if ($read) {
                $read->delete();
                if ($book->favorites()->doesntExist() && $book->reads()->doesntExist() && $book->reviews()->doesntExist()) {
                    $book->delete();
                }
            } else {
                $user->readBooks()->attach($book->id, [
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                if ($favorite) {
                    $favorite->delete();
                }
            }
        });

        return response()->json(['status' => 'success']);
    }
}

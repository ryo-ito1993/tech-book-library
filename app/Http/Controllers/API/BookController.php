<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Book;
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

        \DB::transaction(function () use ($user, $isbn, $request) {
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
                if ($book->favorites()->doesntExist() && $book->reviews()->doesntExist()) {
                    $book->delete();
                }
            } else {
                $user->favoriteBooks()->attach($book->id, [
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);            }
        });

        return response()->json(['status' => 'success']);
    }
}

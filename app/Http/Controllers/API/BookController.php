<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function toggleFavorite(Request $request)
    {
        $user = User::findorFail($request->input('user_id'));
        $isbn = $request->input('isbn');

        $favorite = $user->favoriteBooks()->where('isbn', $isbn)->first();

        if ($favorite) {
            $favorite->delete();
        } else {
            $user->favoriteBooks()->create(['isbn' => $isbn]);
        }

        \Log::info("user_id: {$user->id}, isbn: {$isbn} {$favorite}");

        return response()->json(['status' => 'success']);
    }
}

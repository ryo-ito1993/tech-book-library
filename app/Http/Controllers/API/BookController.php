<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function toggleFavorite(Request $request)
    {
        $user = auth()->user();
        $isbn = $request->input('isbn');

        $favorite = $user->favoriteBooks()->where('isbn', $isbn)->first();

        if ($favorite) {
            $favorite->delete();
        } else {
            $user->favoriteBooks()->create(['isbn' => $isbn]);
        }

        return response()->json(['status' => 'success']);
    }
}

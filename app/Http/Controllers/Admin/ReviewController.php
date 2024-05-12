<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $searchInput = [
            'reviewer' => $request->input('reviewer'),
            'bookName' => $request->input('bookName'),
        ];
        $reviews = ReviewService::search($searchInput)->orderBy('created_at', 'desc')->paginate(30);

        return view('admin.reviews.index', ['reviews' => $reviews]);
    }

    public function show(Review $review)
    {
        return view('admin.reviews.show', ['review' => $review]);
    }

    public function destroy(Review $review): RedirectResponse
    {
        $book = $review->book;

        $review->delete();
        if ($book->favorites()->doesntExist() && $book->reviews()->doesntExist()) {
            $book->delete();
        }

        return redirect()->route('admin.reviews.index')->with('status', 'レビューを削除しました');
    }

}

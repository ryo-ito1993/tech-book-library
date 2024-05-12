<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Services\ReviewService;

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
}

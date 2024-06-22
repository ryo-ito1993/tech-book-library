<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Services\BookService;
use App\Services\ReviewService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function __construct(
        protected ReviewService $reviewService,
        protected BookService $bookService
    ) {
    }

    public function index(Request $request): View
    {
        $searchInput = [
            'reviewer' => $request->input('reviewer'),
            'bookName' => $request->input('bookName'),
        ];
        $reviews = $this->reviewService->search($searchInput)->orderBy('created_at', 'desc')->paginate(30);

        return view('admin.reviews.index', ['reviews' => $reviews]);
    }

    public function show(Review $review): View
    {
        return view('admin.reviews.show', ['review' => $review]);
    }

    public function destroy(Review $review): RedirectResponse
    {
        $book = $review->book;

        $review->delete();
        if ($this->bookService->hasNoRelations($book)) {
            $book->delete();
        }

        return redirect()->route('admin.reviews.index')->with('status', 'レビューを削除しました');
    }
}

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreReviewRequest;
use App\Http\Requests\User\UpdateReviewRequest;
use App\Library\googleBooksApiLibrary;
use App\Models\Book;
use App\Models\Category;
use App\Models\LevelCategory;
use App\Models\ReviewLevelCategory;
use App\Models\Review;
use App\Models\ReviewCategory;
use App\Services\ReviewService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct(
        protected googleBooksApiLibrary $googleBooksApiLibrary,
        protected ReviewService $reviewService
    ) {
    }

    public function index(Request $request): View
    {
        $user = auth()->user();
        $searchInput = [
            'category' => $request->input('category'),
            'levelCategory' => $request->input('levelCategory'),
        ];
        $reviews = $this->reviewService->searchCategory($searchInput)->orderBy('created_at', 'desc')->paginate(10);

        return view('user.reviews.index', ['reviews' => $reviews, 'user' => $user]);
    }

    public function create(string $isbn): View
    {
        $book = $this->googleBooksApiLibrary->getBookByIsbn($isbn);
        $categories = Category::all();
        $levelCategories = LevelCategory::all();

        return view('user.reviews.create', ['book' => $book, 'categories' => $categories, 'levelCategories' => $levelCategories]);
    }

    public function store(StoreReviewRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $user = auth()->user();
        $this->reviewService->storeReview($validated, $user->id);

        return redirect()->route('user.books.show', ['isbn' => $validated['isbn']])->with('status', 'レビューを投稿しました');
    }

    public function edit(Review $review): View
    {
        $isbn = $review->book->isbn;
        $book = $this->googleBooksApiLibrary->getBookByIsbn($isbn);
        $categories = Category::all();
        $levelCategories = LevelCategory::all();

        return view('user.reviews.edit', ['review' => $review, 'book' => $book, 'categories' => $categories, 'levelCategories' => $levelCategories]);
    }

    public function update(UpdateReviewRequest $request, Review $review): RedirectResponse
    {
        $validated = $request->validated();
        $isbn = $review->book->isbn;

        \DB::transaction(static function () use ($validated, $review) {
            $review->body = $validated['review'];
            $review->rate = $validated['rating'];
            $review->save();

            $review->categories()->sync($validated['categories'] ?? []);
            $review->levelCategories()->sync($validated['levelCategories'] ?? []);
        });

        return redirect()->route('user.books.show', ['isbn' => $isbn])->with('status', 'レビューを更新しました');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $book = $review->book;

        $review->delete();
        if ($book->favorites()->doesntExist() && $book->reads()->doesntExist() && $book->reviews()->doesntExist()) {
            $book->delete();
        }

        return redirect()->back()->with('status', 'レビューを削除しました');
    }
}

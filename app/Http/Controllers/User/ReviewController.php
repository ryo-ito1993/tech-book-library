<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreReviewRequest;
use App\Http\Requests\User\UpdateReviewRequest;
use App\Library\googleBooksApiLibrary;
use App\Models\Book;
use App\Models\Category;
use App\Models\Review;
use App\Models\ReviewCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function __construct(
        protected googleBooksApiLibrary $googleBooksApiLibrary
    ) {
    }

    public function index(): View
    {
        $user = auth()->user();
        $reviews = Review::with(['book.authors', 'user', 'categories'])->latest()->paginate(10);

        return view('user.reviews.index', ['reviews' => $reviews, 'user' => $user]);
    }

    public function create(string $isbn): View
    {
        $book = $this->googleBooksApiLibrary->getBookByIsbn($isbn);
        $categories = Category::all();

        return view('user.reviews.create', ['book' => $book, 'categories' => $categories]);
    }

    public function store(StoreReviewRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $user = auth()->user();
        $isbn = $validated['isbn'];

        \DB::transaction(function () use ($validated, $user, $isbn) {
            $book = Book::firstOrCreate(
                ['isbn' => $isbn],
                ['title' => $validated['title'], 'thumbnail' => $validated['thumbnail']]
            );
            if ($book->authors()->doesntExist()) {
                $authors = $validated['authors'];
                foreach ($authors as $author) {
                    $book->authors()->firstOrCreate(['name' => $author]);
                }
            }

            $review = new Review();
            $review->user_id = $user->id;
            $review->book_id = $book->id;
            $review->body = $validated['review'];
            $review->rate = $validated['rating'];
            $review->save();

            if (isset($validated['categories'])) {
                foreach ($validated['categories'] as $categoryId) {
                    ReviewCategory::create(['review_id' => $review->id, 'category_id' => $categoryId]);
                }
            }
        });

        return redirect()->route('user.books.show', ['isbn' => $isbn])->with('status', 'レビューを投稿しました');
    }

    public function edit(Review $review): View
    {
        $isbn = $review->book->isbn;
        $book = $this->googleBooksApiLibrary->getBookByIsbn($isbn);
        $categories = Category::all();

        return view('user.reviews.edit', ['review' => $review, 'categories' => $categories, 'book' => $book]);
    }

    public function update(UpdateReviewRequest $request, Review $review): RedirectResponse
    {
        $validated = $request->validated();
        $isbn = $review->book->isbn;

        \DB::transaction(function () use ($validated, $review) {
            $review->body = $validated['review'];
            $review->rate = $validated['rating'];
            $review->save();

            $review->categories()->sync($validated['categories'] ?? []);
        });

        return redirect()->route('user.books.show', ['isbn' => $isbn])->with('status', 'レビューを更新しました');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $book = $review->book;

        $review->delete();
        if ($book->favorites()->doesntExist() && $book->reviews()->doesntExist()) {
            $book->delete();
        }

        return redirect()->back()->with('status', 'レビューを削除しました');
    }
}

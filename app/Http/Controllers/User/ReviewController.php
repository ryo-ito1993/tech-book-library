<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Library\googleBooksApiLibrary;
use App\Models\Category;
use App\Models\Book;
use App\Models\ReviewCategory;
use Illuminate\Http\RedirectResponse;
use App\Models\Review;

class ReviewController extends Controller
{
    public function __construct(
        protected googleBooksApiLibrary $googleBooksApiLibrary
    ) {
    }

    public function index(): View
    {
        return view('user.reviews.index');
    }

    public function create(string $isbn): View
    {
        $book = $this->googleBooksApiLibrary->getBookByIsbn($isbn);
        $categories = Category::all();
        return view('user.reviews.create', ['book' => $book, 'categories' => $categories]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $isbn = $request->input('isbn');
        $book = Book::firstOrCreate(
            ['isbn' => $isbn],
            ['title' => $request->input('title'), 'thumbnail' => $request->input('thumbnail')]
        );

        $review = new Review();
        $review->user_id = $user->id;
        $review->book_id = $book->id;
        $review->body = $request->input('review');
        $review->rate = $request->input('rating');
        $review->save();

        if ($request->input('categories')) {
            foreach ($request->input('categories') as $categoryId) {
                ReviewCategory::create(['review_id' => $review->id, 'category_id' => $categoryId]);
            }
        }
        return redirect()->route('user.books.show', ['isbn' => $isbn])->with('status', 'レビューを投稿しました');
    }
}

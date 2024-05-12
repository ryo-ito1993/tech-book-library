<?php

namespace App\Services;

use App\Models\Review;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Book;
use App\Models\ReviewCategory;
use App\Models\ReviewLevelCategory;

class ReviewService
{
    // 管理画面検索
    public static function search(array $input): Builder
    {
        $query = Review::with(['book', 'user']);

        if (isset($input['reviewer'])) {
            $query->whereHas('user', function ($query) use ($input) {
                $query->where('name', 'like', '%' . $input['reviewer'] . '%');
            });
        }

        if (isset($input['bookName'])) {
            $query->whereHas('book', function ($query) use ($input) {
                $query->where('title', 'like', '%' . $input['bookName'] . '%');
            });
        }

        return $query;
    }

    // ユーザー側カテゴリ検索
    public static function searchCategory(array $input): Builder
    {
        $query = Review::with(['book.authors', 'user', 'categories', 'levelCategories']);

        if (isset($input['category'])) {
            $query->whereHas('categories', static function ($query) use ($input) {
                $query->where('categories.id', $input['category']);
            });
        }

        if (isset($input['levelCategory'])) {
            $query->whereHas('levelCategories', static function ($query) use ($input) {
                $query->where('level_categories.id', $input['levelCategory']);
            });
        }

        return $query;
    }

    // レビュー登録
    public function storeReview(array $validated, int $userId): Review
    {
        return \DB::transaction(function () use ($validated, $userId) {
            $book = Book::firstOrCreate(
                ['isbn' => $validated['isbn']],
                ['title' => $validated['title'], 'thumbnail' => $validated['thumbnail']]
            );

            if ($book->authors()->doesntExist()) {
                foreach ($validated['authors'] as $author) {
                    $book->authors()->firstOrCreate(['name' => $author]);
                }
            }

            $review = new Review([
                'user_id' => $userId,
                'book_id' => $book->id,
                'body'    => $validated['review'],
                'rate'    => $validated['rating']
            ]);
            $review->save();

            $this->attachCategories($review, $validated['categories'] ?? []);
            $this->attachLevelCategories($review, $validated['levelCategories'] ?? []);

            return $review;
        });
    }

    private function attachCategories(Review $review, array $categories): void
    {
        foreach ($categories as $categoryId) {
            ReviewCategory::create(['review_id' => $review->id, 'category_id' => $categoryId]);
        }
    }

    private function attachLevelCategories(Review $review, array $levelCategories): void
    {
        foreach ($levelCategories as $levelCategoryId) {
            ReviewLevelCategory::create(['review_id' => $review->id, 'level_category_id' => $levelCategoryId]);
        }
    }
}

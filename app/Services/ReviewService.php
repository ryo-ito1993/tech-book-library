<?php

namespace App\Services;

use App\Models\Review;
use Illuminate\Database\Eloquent\Builder;

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
}

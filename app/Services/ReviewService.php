<?php

namespace App\Services;

use App\Models\Review;
use Illuminate\Database\Eloquent\Builder;

class ReviewService
{
    // 検索
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
}

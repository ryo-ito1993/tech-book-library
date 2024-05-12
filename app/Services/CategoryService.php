<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;

class CategoryService
{
    // 検索
    public static function search(array $input): Builder
    {
        $query = Category::query();

        if (isset($input['categoryName'])) {
            $query->where('name', 'like', '%' . $input['categoryName'] . '%');
        }

        return $query;
    }
}

<?php

namespace App\Services;

use App\Models\LevelCategory;
use Illuminate\Database\Eloquent\Builder;

class LevelCategoryService
{
    // 検索
    public static function search(array $input): Builder
    {
        $query = LevelCategory::query();

        if (isset($input['levelCategoryName'])) {
            $query->where('name', 'like', '%' . $input['levelCategoryName'] . '%');
        }

        return $query;
    }
}

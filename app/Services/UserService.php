<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserService
{
    // 検索
    public static function search(array $input): Builder
    {
        $query = User::query();

        if (isset($input['userName'])) {
            $query->where('name', 'like', '%' . $input['userName'] . '%');
        }

        if (isset($input['email'])) {
            $query->where('email', 'like', '%' . $input['email'] . '%');
        }

        if (isset($input['library'])) {
            if ($input['library'] === '未登録') {
                $query->doesntHave('library');
            } else {
                $query->whereHas('library', function ($query) use ($input) {
                    $query->where('system_name', 'like', '%' . $input['library'] . '%');
                });
            }
        }

        return $query;
    }
}

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

        return $query;
    }
}

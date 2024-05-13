<?php

namespace App\Services;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Builder;

class ContactService
{
    // 検索
    public static function search(array $input): Builder
    {
        $query = Contact::query();

        if (isset($input['contactName'])) {
            $query->where('name', 'like', '%' . $input['contactName'] . '%');
        }

        if (isset($input['email'])) {
            $query->where('email', 'like', '%' . $input['email'] . '%');
        }

        return $query;
    }
}

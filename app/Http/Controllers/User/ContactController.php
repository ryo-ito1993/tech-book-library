<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\User\StoreContactRequest;

class ContactController extends Controller
{
    public function create()
    {
        return view('user.contacts.create');
    }

    public function confirm(StoreContactRequest $request)
    {
        $validated = $request->validated();

        return view('user.contacts.confirm', ['contact' => $validated]);
    }

    public function back()
    {
        return redirect()->route('user.contacts.create')->withInput();
    }
}

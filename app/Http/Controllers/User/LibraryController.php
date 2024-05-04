<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Library;
use Illuminate\Http\RedirectResponse;

class LibraryController extends Controller
{
    public function create(): View
    {
        return view('user.libraries.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $systemId = $request->input('systemid');
        $systemName = $request->input('systemname');

        Library::updateOrCreate(
            ['user_id' => $user->id],
            [   'system_id' => $systemId,
                'system_name' => $systemName
            ]
        );

        return redirect()->back()->with('status', 'お気に入り図書館を登録しました');
    }
}

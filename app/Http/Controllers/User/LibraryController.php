<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Library;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\User\StoreLibraryRequest;

class LibraryController extends Controller
{
    public function create(): View
    {
        return view('user.libraries.create');
    }

    public function store(StoreLibraryRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $validatedData = $request->validated();

        Library::updateOrCreate(
            ['user_id' => $user->id],
            [   'system_id' => $validatedData['systemid'],
                'system_name' => $validatedData['systemname'],
            ]
        );

        return redirect()->back()->with('status', 'お気に入り図書館を登録しました');
    }
}

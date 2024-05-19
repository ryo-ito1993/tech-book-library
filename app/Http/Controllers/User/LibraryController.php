<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreLibraryRequest;
use App\Library\calilApiLibrary;
use App\Models\Library;
use App\Models\Prefecture;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LibraryController extends Controller
{
    public function __construct(
        protected CalilApiLibrary $calilApiLibrary,
    ) {
    }

    public function create(): View
    {
        $userLibrary = auth()->user()->library;
        $userLibraries = [];
        $prefectures = Prefecture::all();

        if ($userLibrary) {
            $userLibraries = $this->calilApiLibrary->getLibrariesBySystemId($userLibrary->system_id);
        }

        return view('user.libraries.create', ['userLibrary' => $userLibrary, 'userLibraries' => $userLibraries, 'prefectures' => $prefectures]);
    }

    public function store(StoreLibraryRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $validatedData = $request->validated();

        Library::updateOrCreate(
            ['user_id' => $user->id],
            ['system_id' => $validatedData['systemid'],
                'system_name' => $validatedData['systemname'],
            ]
        );

        return redirect()->back()->with('status', 'お気に入り図書館を登録しました');
    }

    public function destroy(Library $library): RedirectResponse
    {
        $library->delete();

        return redirect()->back()->with('status', 'お気に入り図書館を削除しました');
    }
}

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Library;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\User\StoreLibraryRequest;
use Illuminate\Support\Facades\Http;
use App\Library\CalilApiLibrary;
use App\Models\Prefecture;

class LibraryController extends Controller
{
    protected $calilApiLibrary;

    public function __construct(CalilApiLibrary $calilApiLibrary)
    {
        $this->calilApiLibrary = $calilApiLibrary;
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
            [   'system_id' => $validatedData['systemid'],
                'system_name' => $validatedData['systemname'],
            ]
        );

        return redirect()->back()->with('status', 'お気に入り図書館を登録しました');
    }
}

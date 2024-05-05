<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Library;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\User\StoreLibraryRequest;
use Illuminate\Support\Facades\Http;

class LibraryController extends Controller
{
    public function create(): View
    {
        $userLibrary = auth()->user()->library;
        $userLibraries = [];

        if ($userLibrary) {
            $response = Http::get("https://api.calil.jp/library", [
                'appkey' => env('CALIL_APP_KEY'),
                'systemid' => $userLibrary->system_id,
                'format' => 'json',
                'callback' => 'no',
            ]);
            if ($response->successful()) {
                $jsonResponse = $response->json();
                $userLibraries = array_values($jsonResponse);
            }
        }
        return view('user.libraries.create', ['userLibrary' => $userLibrary, 'userLibraries' => $userLibraries]);
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

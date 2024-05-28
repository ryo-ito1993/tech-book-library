<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Requests\User\ChangePasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function edit(): View
    {
        return view('user.passwords.edit');
    }

    public function update(ChangePasswordRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        return redirect()->back()->with('status', 'パスワードを変更しました');
    }
}

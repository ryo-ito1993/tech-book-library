<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    // ゲストユーザー用のユーザーIDを定数として定義
    private const GUEST_USER_ID = 1;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Determine where to redirect users after login.
     *
     * @return string
     */
    protected function redirectTo()
    {
        $user = Auth::user();

        return $user->library ? '/user/books/search' : '/user/library';
    }

    // ゲストログイン処理
    public function guestLogin(): RedirectResponse
    {
        if (Auth::loginUsingId(self::GUEST_USER_ID)) {
            return redirect('/user/library')->with('status', 'ゲストユーザーとしてログインしました。');
        }

        return redirect('/')->with('flash_message', 'ゲストログインに失敗しました。');
    }
}

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Requests\User\ChangeEmailRequest;
use App\Models\UserEmailReset;
use App\Models\User;
use App\Mail\UserChangeEmailMail;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChangeEmailController extends Controller
{
    /**
     * トークンの有効期限(30分)
     */
    const TOKEN_EXPIRES = 60 * 30;

    // 新しいメールアドレス送信フォーム表示
    public function edit(): View
    {
        $currentEmail = auth()->user()->email;
        return view('user.emails.edit', ['currentEmail' => $currentEmail]);
    }

    // メールアドレス変更確認メール送信
    public function sendChangeEmailLink(ChangeEmailRequest $request): RedirectResponse
    {
        $newEmail = $request->email;

        // トークン生成
        $token = hash_hmac(
            'sha256',
            \Str::random(40) . $newEmail,
            config('app.key')
        );

    // トークンをDBに保存
    try {
        DB::transaction(function () use ($newEmail, $token) {
            UserEmailReset::create([
                'user_id' => Auth::id(),
                'new_email' => $newEmail,
                'token' => $token,
            ]);

            \Mail::to($newEmail)->send(new UserChangeEmailMail($token));
        });

        return redirect()->back()->with('status', '確認メールを送信しました。');
    } catch (\Exception $e) {
        \Log::error('メール更新に失敗しました：' . $e->getMessage());
        return redirect()->back()->with('flash_alert', 'メール更新に失敗しました。');
    }
    }

    // メールアドレス変更（更新）
    public function updateEmail(string $token): RedirectResponse
    {
        // トークンから新しいメールアドレスとユーザーIDが入っているレコードを取得
        $emailResets = UserEmailReset::where('token', $token)->first();

        // トークンが存在していて、かつ、有効期限が切れていないかチェック
        if ($emailResets && !$this->tokenExpired($emailResets->created_at)) {
            // ユーザーのメールアドレスを更新
            $user = User::find($emailResets->user_id);
            $user->email = $emailResets->new_email;
            $user->save();

            // レコードを削除
            $emailResets->delete();

            return redirect()->route('user.top')->with('status', 'メールアドレスを更新しました！');
        }
        // レコードが存在していた場合削除
        if ($emailResets) {
            $emailResets->delete();
        }
        return redirect()->route('user.login')->with('flash_alert', 'トークンの有効期限が切れているか、トークンが不正です。');
    }

    /**
     * * トークンが有効期限切れかどうかチェック
     *
     * @param string $createdAt
     *
     * @return bool
     */
    protected function tokenExpired($createdAt)
    {
        return Carbon::parse($createdAt)->addSeconds(static::TOKEN_EXPIRES)->isPast();
    }
}

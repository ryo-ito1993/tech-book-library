<?php

namespace Tests\Feature\Controllers\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\UserEmailReset;
use App\Mail\UserChangeEmailMail;
use Illuminate\Support\Facades\Mail;

class ChangeEmailControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testEdit(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('user.emails.edit'));

        $response->assertStatus(200);
        $response->assertViewIs('user.emails.edit');
        $response->assertViewHas('currentEmail', $user->email);
    }

    public function testSendChangeEmailLink(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        $this->actingAs($user);

        $newEmail = 'new@example.com';

        $response = $this->post(route('user.emails.send'), [
            'email' => $newEmail,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('status', '確認メールを送信しました。');

        $this->assertDatabaseHas('user_email_resets', [
            'user_id' => $user->id,
            'new_email' => $newEmail,
        ]);

        Mail::assertSent(UserChangeEmailMail::class, function ($mail) use ($newEmail) {
            return $mail->hasTo($newEmail);
        });
    }

    public function testUpdateEmail(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $newEmail = 'new@example.com';
        $token = hash_hmac('sha256', \Str::random(40) . $newEmail, config('app.key'));

        UserEmailReset::create([
            'user_id' => $user->id,
            'new_email' => $newEmail,
            'token' => $token,
        ]);

        $response = $this->get(route('user.emails.update', ['token' => $token]));

        $response->assertStatus(302);
        $response->assertRedirect(route('user.top'));
        $response->assertSessionHas('status', 'メールアドレスを更新しました！');

        $this->assertDatabaseMissing('user_email_resets', [
            'token' => $token,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => $newEmail,
        ]);
    }

    public function testUpdateEmailWithExpiredToken(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $newEmail = 'new@example.com';
        $token = hash_hmac('sha256', \Str::random(40) . $newEmail, config('app.key'));

        $expiredTimestamp = now()->subMinutes(31);

        $emailReset = UserEmailReset::create([
            'user_id' => $user->id,
            'new_email' => $newEmail,
            'token' => $token,
        ]);

        $emailReset->created_at = $expiredTimestamp;
        $emailReset->updated_at = $expiredTimestamp;
        $emailReset->save();

        $response = $this->get(route('user.emails.update', ['token' => $token]));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('flash_alert', 'トークンの有効期限が切れているか、トークンが不正です。');

        $this->assertDatabaseMissing('user_email_resets', [
            'token' => $token,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => $user->email,
        ]);
    }
}

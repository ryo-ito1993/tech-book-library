<?php

namespace Tests\Feature\Controllers\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class ChangePasswordControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testEdit(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('user.passwords.edit'));

        $response->assertStatus(200);
        $response->assertViewIs('user.change_password');
    }

    public function testUpdate(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = [
            'now_password' => 'password',
            'new_password' => 'new_password',
            'new_password_confirmation' => 'new_password',
        ];

        $response = $this->patch(route('user.passwords.update'), $data);

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $response->assertSessionHas('status', 'パスワードを変更しました');
    }

    public function testUpdateValidation(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = [
            'now_password' => 'invalid_password',
            'new_password' => 'new_password',
            'new_password_confirmation' => 'new_password',
        ];

        $response = $this->patch(route('user.passwords.update'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('now_password');
    }
}

<?php

namespace Tests\Feature\Controllers\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Admin;
use App\Models\Library;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testIndex(): void
    {
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');
        $library = Library::factory()->create();
        $user = $library->user;
        $request =[
            'userName' => $user->name,
            'email' => $user->email,
            'library' => $library->system_id,
        ];
        $response = $this->get(route('admin.users.index', $request));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.index');
    }

    public function testShow(): void
    {
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');
        $library = Library::factory()->create();
        $user = $library->user;
        $response = $this->get(route('admin.users.show', $user));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.show');
    }
}

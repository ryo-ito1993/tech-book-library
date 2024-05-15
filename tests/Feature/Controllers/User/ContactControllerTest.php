<?php

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserContactMail;
use App\Mail\AdminContactMail;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    public function testCreate(): void
    {
        $response = $this->get(route('user.contacts.create'));
        $response->assertStatus(200);
        $response->assertViewIs('user.contacts.create');
    }

    public function testConfirm(): void
    {
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'message' => $this->faker->paragraph,
        ];

        $response = $this->post(route('user.contacts.confirm'), $data);
        $response->assertStatus(200);
        $response->assertViewIs('user.contacts.confirm');
        $response->assertViewHas('contact', $data);
    }

    public function testStore(): void
    {
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'message' => $this->faker->paragraph,
        ];

        $response = $this->post(route('user.contacts.store'), $data);
        $response->assertStatus(200);
        $response->assertViewIs('user.contacts.complete');
        $this->assertDatabaseHas('contacts', $data);
        Mail::assertSent(UserContactMail::class);
        Mail::assertSent(AdminContactMail::class);
    }

    public function testComplete(): void
    {
        $response = $this->get(route('user.contacts.complete'));
        $response->assertStatus(200);
        $response->assertViewIs('user.contacts.complete');
    }

    public function testBack(): void
    {
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'message' => $this->faker->paragraph,
        ];

        $response = $this->from(route('user.contacts.confirm'))
            ->post(route('user.contacts.back'), $data);

        $response->assertRedirect(route('user.contacts.create'));
        $response->assertSessionHasInput($data);
    }
}

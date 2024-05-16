<?php

namespace Tests\Feature\Controllers\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Admin;
use App\Models\Contact;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');
    }

    public function testIndex(): void
    {
        $response = $this->get(route('admin.contacts.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.contacts.index');
    }

    public function testShow(): void
    {
        $contact = Contact::factory()->create();
        $response = $this->get(route('admin.contacts.show', $contact->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.contacts.show');
    }

    public function testUpdateStatus(): void
    {
        $contact = Contact::factory()->create();
        $response = $this->patch(route('admin.contacts.update_status', $contact->id), ['status' => '1']);

        $response->assertStatus(302);
        $response->assertRedirect();
    }
}

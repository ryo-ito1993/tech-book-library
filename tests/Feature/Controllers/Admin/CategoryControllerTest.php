<?php

namespace Tests\Feature\Controllers\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Admin;
use App\Models\Category;

class CategoryControllerTest extends TestCase
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
        $response = $this->get(route('admin.categories.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.index');
    }

    public function testCreate(): void
    {
        $response = $this->get(route('admin.categories.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.create');
    }

    public function testStore(): void
    {
        $request = [
            'name' => $this->faker->word,
        ];
        $response = $this->post(route('admin.categories.store'), $request);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.categories.index'));
    }

    public function testEdit(): void
    {
        $category = Category::factory()->create();
        $response = $this->get(route('admin.categories.edit', $category->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.edit');
    }

    public function testUpdate(): void
    {
        $category = Category::factory()->create();
        $request = [
            'name' => $this->faker->word,
        ];

        $response = $this->patch(route('admin.categories.update', $category->id), $request);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.categories.index'));
    }

    public function testDestroy(): void
    {
        $category = Category::factory()->create();
        $response = $this->delete(route('admin.categories.destroy', $category->id));

        $response->assertStatus(302);
        $response->assertRedirect();
    }
}

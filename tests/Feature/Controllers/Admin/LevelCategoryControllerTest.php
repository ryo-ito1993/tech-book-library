<?php

namespace Tests\Feature\Controllers\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Admin;
use App\Models\LevelCategory;

class LevelCategoryControllerTest extends TestCase
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
        $response = $this->get(route('admin.level_categories.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.level_categories.index');
    }

    public function testCreate(): void
    {
        $response = $this->get(route('admin.level_categories.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.level_categories.create');
    }

    public function testStore(): void
    {
        $request = [
            'name' => $this->faker->word,
        ];
        $response = $this->post(route('admin.level_categories.store'), $request);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.level_categories.index'));
    }

    public function testEdit(): void
    {
        $levelCategory = LevelCategory::factory()->create();
        $response = $this->get(route('admin.level_categories.edit', $levelCategory->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.level_categories.edit');
    }

    public function testUpdate(): void
    {
        $levelCategory = LevelCategory::factory()->create();
        $request = [
            'name' => $this->faker->word,
        ];
        $response = $this->put(route('admin.level_categories.update', $levelCategory->id), $request);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.level_categories.index'));
    }

    public function testDestroy(): void
    {
        $levelCategory = LevelCategory::factory()->create();
        $response = $this->delete(route('admin.level_categories.destroy', $levelCategory->id));

        $response->assertStatus(302);
        $response->assertRedirect();
    }
}

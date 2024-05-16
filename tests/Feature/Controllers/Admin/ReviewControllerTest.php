<?php

namespace Tests\Feature\Controllers\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Admin;
use App\Models\Review;

class ReviewControllerTest extends TestCase
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
        $response = $this->get(route('admin.reviews.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.reviews.index');
    }

    public function testShow(): void
    {
        $review = Review::factory()->create();
        $response = $this->get(route('admin.reviews.show', $review->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.reviews.show');
    }

    public function testDestroy(): void
    {
        $review = Review::factory()->create();
        $response = $this->delete(route('admin.reviews.destroy', $review->id));

        $response->assertStatus(302);
        $response->assertRedirect();
    }
}

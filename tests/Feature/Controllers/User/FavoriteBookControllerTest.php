<?php

namespace Tests\Feature\Controllers\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Book;

class FavoriteBookControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testIndex()
    {
        $user = User::factory()->create();
        $books = Book::factory(3)->create();
        $user->favoriteBooks()->attach($books->pluck('id'));

        $response = $this->actingAs($user)
            ->get(route('user.favorite_books.index'));

        $response->assertStatus(200);
        $response->assertViewIs('user.favorite_books.index');
        $response->assertViewHas('books', function ($viewBooks) use ($books) {
            return $viewBooks->pluck('id')->diff($books->pluck('id'))->isEmpty();
        });
    }
}

<?php

namespace Tests\Feature\Controllers\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Book;

class BookControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testToggleFavorite(): void
    {
        $user = User::factory()->create();
        $isbn = '1234567890';
        $title = 'Test Book';
        $thumbnail = 'http://example.com/thumbnail.jpg';
        $authors = ['Author 1', 'Author 2'];

        // 初回のお気に入り追加
        $response = $this->postJson(route('api.books.toggleFavorite'), [
            'user_id' => $user->id,
            'isbn' => $isbn,
            'title' => $title,
            'thumbnail' => $thumbnail,
            'authors' => $authors,
        ]);

        $response->assertStatus(200)
            ->assertJson(['status' => 'success']);

        $book = Book::where('isbn', $isbn)->first();

        $this->assertDatabaseHas('books', ['isbn' => $isbn, 'title' => $title, 'thumbnail' => $thumbnail]);
        foreach ($authors as $author) {
            $this->assertDatabaseHas('book_authors', ['name' => $author, 'book_id' => $book->id]);
        }
        $this->assertDatabaseHas('favorite_books', ['user_id' => $user->id, 'book_id' => $book->id]);

        // 2回目のお気に入り削除
        $response = $this->postJson(route('api.books.toggleFavorite'), [
            'user_id' => $user->id,
            'isbn' => $isbn,
            'title' => $title,
            'thumbnail' => $thumbnail,
            'authors' => $authors,
        ]);

        $response->assertStatus(200)
            ->assertJson(['status' => 'success']);

        $this->assertDatabaseMissing('favorite_books', ['user_id' => $user->id, 'book_id' => $book->id]);
        $this->assertDatabaseMissing('books', ['isbn' => $isbn, 'title' => $title, 'thumbnail' => $thumbnail]);
        foreach ($authors as $author) {
            $this->assertDatabaseMissing('book_authors', ['name' => $author, 'book_id' => $book->id]);
        }
    }
}

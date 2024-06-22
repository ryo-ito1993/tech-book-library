<?php

namespace Tests\Feature\Controllers\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Book;

class NotificationBookControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testIndex(): void
    {
        $user = User::factory()->create();
        $books = Book::factory(3)->create();
        $user->notificationBooks()->attach($books->pluck('id'));

        $response = $this->actingAs($user)
            ->get(route('user.notification_books.index'));

        $response->assertStatus(200);
        $response->assertViewIs('user.notification_books.index');
        $response->assertViewHas('books', function ($viewBooks) use ($books) {
            return $viewBooks->pluck('id')->diff($books->pluck('id'))->isEmpty();
        });
    }
}

<?php

namespace Tests\Feature\Controllers\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use App\Services\BookService;
use App\Library\googleBooksApiLibrary;

class BookControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock the dependencies
        $this->googleBooksApiLibrary = $this->mock(googleBooksApiLibrary::class);
        $this->bookService = $this->mock(BookService::class);
    }

    public function testSearch()
    {
        // Create and authenticate a user
        $user = User::factory()->create();
        $this->actingAs($user);

        $searchParams = [
            'title' => 'Test Book 1',
            'author' => 'Test Author 1',
            'isbn' => '1234567890'
        ];

        $mockBooks = [
            ['title' => 'Test Book 1', 'authors' => ['Test Author 1'], 'publishedDate' => '2021-01-01', 'publisher' => 'Test Publisher 1', 'isbn' => '1234567890', 'thumbnail' => 'test.jpg', 'infoLink' => 'https://example.com'],
            ['title' => 'Test Book 2', 'authors' => ['Test Author 2'], 'publishedDate' => '2021-02-01', 'publisher' => 'Test Publisher 2', 'isbn' => '1234567890', 'thumbnail' => 'test.jpg', 'infoLink' => 'https://example.com'],
        ];

        // Set expectations for the BookService mock
        $this->bookService
            ->shouldReceive('searchBooks')
            ->once()
            ->with($searchParams)
            ->andReturn($mockBooks);

        $response = $this->get(route('user.books.search', $searchParams));

        $response->assertStatus(200);
        $response->assertViewIs('user.books.search');
        $response->assertViewHas('books', $mockBooks);
        $response->assertViewHas('title', 'Test Book 1');
        $response->assertViewHas('author', 'Test Author 1');
        $response->assertViewHas('isbn', '1234567890');
        $response->assertViewHas('hasSearched', true);
    }


    public function testShow()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $isbn = '1234567890';
        $mockBook = ['title' => 'Test Book 1', 'authors' => ['Test Author 1'], 'publishedDate' => '2021-01-01', 'publisher' => 'Test Publisher 1', 'isbn' => '1234567890', 'thumbnail' => 'test.jpg', 'infoLink' => 'https://example.com'];


        $this->googleBooksApiLibrary
            ->shouldReceive('getBookByIsbn')
            ->with($isbn)
            ->andReturn($mockBook);

        $bookModel = Book::factory()->create(['isbn' => $isbn]);
        $user->favoriteBooks()->attach($bookModel->id);

        $response = $this->get(route('user.books.show', ['isbn' => $isbn]));

        $response->assertStatus(200);
        $response->assertViewIs('user.books.show');
        $response->assertViewHas('user', $user);
        $response->assertViewHas('book', $mockBook);
        $response->assertViewHas('isFavorite', true);
        $response->assertViewHas('reviews', $bookModel->reviews->sortByDesc('created_at'));
    }
}

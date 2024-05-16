<?php

namespace Tests\Feature\Services;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Services\BookService;
use App\Library\googleBooksApiLibrary;

class BookServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @var googleBooksApiLibrary
     */
    protected $googleBooksApiLibrary;

    /**
     * @var BookService
     */
    protected $bookService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->googleBooksApiLibrary = $this->mock(googleBooksApiLibrary::class);
        $this->bookService = new BookService($this->googleBooksApiLibrary);
    }

    public function testSearchBooks(): void
    {
        $searchParams = [
            'title' => 'Test Book 1',
            'author' => 'Test Author 1',
            'isbn' => '1234567890'
        ];

        $mockBooks = [
            ['title' => 'Test Book 1', 'authors' => ['Test Author 1'], 'publishedDate' => '2021-01-01', 'publisher' => 'Test Publisher 1', 'isbn' => '1234567890', 'thumbnail' => 'test.jpg', 'infoLink' => 'https://example.com'],
            ['title' => 'Test Book 2', 'authors' => ['Test Author 2'], 'publishedDate' => '2021-02-01', 'publisher' => 'Test Publisher 2', 'isbn' => '1234567890', 'thumbnail' => 'test.jpg', 'infoLink' => 'https://example.com'],
        ];

        $this->googleBooksApiLibrary
            ->shouldReceive('searchBooks')
            ->once()
            ->with('intitle:Test Book 1+inauthor:Test Author 1+isbn:1234567890')
            ->andReturn($mockBooks);

        $response = $this->bookService->searchBooks($searchParams);

        $this->assertSame($mockBooks, $response);
    }

    public function testSearchBooksNoParams(): void
    {
        $searchParams = [];

        $response = $this->bookService->searchBooks($searchParams);

        $this->assertSame([], $response);
    }
}

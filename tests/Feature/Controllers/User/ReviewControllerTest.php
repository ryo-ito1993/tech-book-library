<?php

namespace Tests\Feature\Controllers\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Review;
use App\Services\ReviewService;
use App\Library\googleBooksApiLibrary;
use Mockery;

class ReviewControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // モックのセットアップ
        $this->googleBooksApiLibrary = $this->mock(googleBooksApiLibrary::class);
        $this->reviewService = $this->mock(ReviewService::class);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testIndex()
    {
        $user = User::factory()->create();

        // モックの設定
        $this->reviewService
            ->shouldReceive('searchCategory')
            ->once()
            ->andReturn(Review::query());

        $response = $this->actingAs($user)->get(route('user.reviews.index'));

        $response->assertStatus(200);
        $response->assertViewIs('user.reviews.index');
        $response->assertViewHas('reviews');
        $response->assertViewHas('user', $user);
    }

    public function testCreate()
    {
        $user = User::factory()->create();
        $isbn = '1234567890';

        $this->googleBooksApiLibrary
            ->shouldReceive('getBookByIsbn')
            ->once()
            ->with($isbn)
            ->andReturn([
                'title' => 'Sample Book',
                'authors' => ['Author 1'],
                'publishedDate' => '2021-01-01',
                'publisher' => 'Publisher 1',
                'isbn' => $isbn,
                'thumbnail' => 'http://example.com/image.jpg',
                'infoLink' => 'http://example.com/info'
            ]);

        $response = $this->actingAs($user)->get(route('user.reviews.create', $isbn));

        $response->assertStatus(200);
        $response->assertViewIs('user.reviews.create');
    }

    public function testStore()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $reviewData = [
            'isbn' => '1234567890',
            'title' => 'Sample Book',
            'thumbnail' => 'http://example.com/image.jpg',
            'authors' => ['Author 1', 'Author 2'],
            'review' => 'Great book!',
            'rating' => 5,
            'categories' => [],
            'levelCategories' => [],
        ];

        $this->reviewService
            ->shouldReceive('storeReview')
            ->once()
            ->with($reviewData, $user->id);

        $response = $this->post(route('user.reviews.store'), $reviewData);

        $response->assertRedirect(route('user.books.show', ['isbn' => $reviewData['isbn']]));
    }

    public function testEdit()
    {
        $user = User::factory()->create();
        $review = Review::factory()->create();

        $this->googleBooksApiLibrary
            ->shouldReceive('getBookByIsbn')
            ->once()
            ->with($review->book->isbn)
            ->andReturn([
                'title' => $review->book->title,
                'authors' => ['Author 1'],
                'publishedDate' => '2021-01-01',
                'publisher' => 'Publisher 1',
                'isbn' => $review->book->isbn,
                'thumbnail' => $review->book->thumbnail,
                'infoLink' => 'http://example.com/info'
            ]);

        $response = $this->actingAs($user)->get(route('user.reviews.edit', $review->id));

        $response->assertStatus(200);
        $response->assertViewIs('user.reviews.edit');
    }

    public function testUpdate()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $review = Review::factory()->create();

        $updatedData = [
            'isbn' => $review->book->isbn,
            'title' => $review->book->title,
            'thumbnail' => $review->book->thumbnail,
            'authors' => ['Author 1', 'Author 2'],
            'review' => 'Great book!',
            'rating' => 5,
            'categories' => [],
            'levelCategories' => [],

        ];

        $response = $this->put(route('user.reviews.update', $review->id), $updatedData);

        $response->assertRedirect(route('user.books.show', ['isbn' => $review->book->isbn]));
    }

    public function testDestroy()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $review = Review::factory()->create();

        $response = $this->delete(route('user.reviews.destroy', $review->id));

        $response->assertRedirect();
    }





}

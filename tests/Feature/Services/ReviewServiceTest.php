<?php

namespace Tests\Feature\Services;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use App\Models\Review;
use App\Services\ReviewService;
use App\Models\Category;
use App\Models\LevelCategory;
use App\Models\ReviewCategory;
use App\Models\ReviewLevelCategory;

class ReviewServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user1 = User::create(['name' => 'Yamada Taro', 'email' => 'test1@example.com', 'password' => 'password']);
        $user2 = User::create(['name' => 'Tanaka Taro', 'email' => 'test2@example.com', 'password' => 'password']);
        $book1 = Book::create(['isbn' => '1234567890', 'title' => 'test_book1', 'thumbnail' => 'test.jpg']);
        $book2 = Book::create(['isbn' => '0987654321', 'title' => 'test_book2', 'thumbnail' => 'test.jpg']);
        $category = Category::create(['name' => 'test_category']);
        $lavelCategory = LevelCategory::create(['name' => 'test_level_category']);
        $review1 = Review::create(['user_id' => $user1->id, 'book_id' => $book1->id, 'body' => 'Good', 'rate' => 5]);
        $review2 = Review::create(['user_id' => $user2->id, 'book_id' => $book2->id, 'body' => 'Bad', 'rate' => 1]);
        ReviewCategory::create(['review_id' => $review1->id, 'category_id' => $category->id]);
        ReviewLevelCategory::create(['review_id' => $review2->id, 'level_category_id' => $lavelCategory->id]);
    }

    public function testSearchByUserName()
    {
        $result = ReviewService::search(['reviewer' => 'Yamada'])->get();
        $this->assertCount(1, $result);
        $this->assertEquals('Yamada Taro', $result->first()->user->name);
    }

    public function testSearchByBookTitle()
    {
        $result = ReviewService::search(['bookName' => 'book1'])->get();
        $this->assertCount(1, $result);
        $this->assertEquals('test_book1', $result->first()->book->title);
    }

    public function testSerchMultiple()
    {
        $result = ReviewService::search(['reviewer' => 'Taro', 'bookName' => 'book2'])->get();
        $this->assertCount(1, $result);
        $this->assertEquals('Tanaka Taro', $result->first()->user->name);
        $this->assertEquals('test_book2', $result->first()->book->title);
    }

    public function testSearchCategory()
    {
        $category = Category::where('name', 'test_category')->first();
        $result = ReviewService::searchCategory(['category' => $category->id])->get();
        $this->assertCount(1, $result);
        $this->assertEquals('test_category', $result->first()->categories->first()->name);
    }

    public function testSearchLevelCategory()
    {
        $levelCategory = LevelCategory::where('name', 'test_level_category')->first();
        $result = ReviewService::searchCategory(['levelCategory' => $levelCategory->id])->get();
        $this->assertCount(1, $result);
        $this->assertEquals('test_level_category', $result->first()->levelCategories->first()->name);
    }

    public function testStoreReview()
    {
        $service = new ReviewService();
        $review = Review::where('body', 'Good')->first();
        $category = Category::where('name', 'test_category')->first();
        $levelCategory = LevelCategory::where('name', 'test_level_category')->first();
        $validated = [
            'isbn' => $review->book->isbn,
            'title' => $review->book->title,
            'thumbnail' => $review->book->thumbnail,
            'authors' => ['test_author'],
            'review' => $review->body,
            'rating' => $review->rate,
            'categories' => [$category->id],
            'levelCategories' => [$levelCategory->id],
        ];
        $user = User::where('name', 'Yamada Taro')->first();
        $result = $service->storeReview($validated, $user->id);
        $this->assertEquals('Good', $result->body);
        $this->assertEquals(5, $result->rate);
        $this->assertEquals('test_book1', $result->book->title);
        $this->assertEquals('test_author', $result->book->authors->first()->name);
        $this->assertEquals('test_category', $result->categories->first()->name);
        $this->assertEquals('test_level_category', $result->levelCategories->first()->name);
    }
}

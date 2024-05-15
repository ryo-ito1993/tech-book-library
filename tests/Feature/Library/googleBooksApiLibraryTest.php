<?php

namespace Tests\Feature\Library;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Library\googleBooksApiLibrary;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class googleBooksApiLibraryTest extends TestCase
{
    public function testSearchBooksSuccess()
    {
        // モックするレスポンスデータ
        $mockResponse = [
            'items' => [
                [
                    'volumeInfo' => [
                        'title' => 'Test Book',
                        'authors' => ['Author One', 'Author Two'],
                        'publishedDate' => '2020-01-01',
                        'publisher' => 'Test Publisher',
                        'imageLinks' => [
                            'thumbnail' => 'http://example.com/thumbnail.jpg'
                        ],
                        'industryIdentifiers' => [
                            ['type' => 'ISBN_13', 'identifier' => '1234567890123']
                        ],
                        'infoLink' => 'http://example.com/info'
                    ]
                ]
            ]
        ];

        // HTTPリクエストのモック
        Http::fake([
            '*' => Http::response($mockResponse, 200)
        ]);

        // テスト対象のメソッドを呼び出す
        $library = new googleBooksApiLibrary();
        $result = $library->searchBooks('test query');

        // 結果のアサーション
        $this->assertCount(1, $result);
        $this->assertEquals('Test Book', $result[0]['title']);
        $this->assertEquals(['Author One', 'Author Two'], $result[0]['authors']);
        $this->assertEquals('2020-01-01', $result[0]['publishedDate']);
        $this->assertEquals('Test Publisher', $result[0]['publisher']);
        $this->assertEquals('1234567890123', $result[0]['isbn']);
        $this->assertEquals('http://example.com/thumbnail.jpg', $result[0]['thumbnail']);
        $this->assertEquals('http://example.com/info', $result[0]['infoLink']);
    }

    public function testSearchBooksApiFailure()
    {
        // HTTPリクエストのモック（エラーレスポンス）
        Http::fake([
            '*' => Http::response('API Error', 500)
        ]);

        // テスト対象のメソッドを呼び出す
        $library = new googleBooksApiLibrary();

        // 例外が投げられることを期待する
        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('API request failed: API Error');

        $library->searchBooks('test query');
    }

    public function testGetBookByIsbnSuccess()
    {
        // モックするレスポンスデータ
        $mockResponse = [
            'items' => [
                [
                    'volumeInfo' => [
                        'title' => 'Test Book',
                        'authors' => ['Author One', 'Author Two'],
                        'publishedDate' => '2020-01-01',
                        'publisher' => 'Test Publisher',
                        'imageLinks' => [
                            'thumbnail' => 'http://example.com/thumbnail.jpg'
                        ],
                        'industryIdentifiers' => [
                            ['type' => 'ISBN_13', 'identifier' => '1234567890123']
                        ],
                        'infoLink' => 'http://example.com/info'
                    ]
                ]
            ]
        ];

        // HTTPリクエストのモック
        Http::fake([
            '*' => Http::response($mockResponse, 200)
        ]);

        // テスト対象のメソッドを呼び出す
        $library = new googleBooksApiLibrary();
        $result = $library->getBookByIsbn('1234567890123');

        // 結果のアサーション
        $this->assertNotNull($result);
        $this->assertEquals('Test Book', $result['title']);
        $this->assertEquals(['Author One', 'Author Two'], $result['authors']);
        $this->assertEquals('2020-01-01', $result['publishedDate']);
        $this->assertEquals('Test Publisher', $result['publisher']);
        $this->assertEquals('1234567890123', $result['isbn']);
        $this->assertEquals('http://example.com/thumbnail.jpg', $result['thumbnail']);
        $this->assertEquals('http://example.com/info', $result['infoLink']);
    }

    public function testGetBookByIsbnNotFound()
    {
        // HTTPリクエストのモック（空のレスポンス）
        Http::fake([
            '*' => Http::response(['items' => []], 200)
        ]);

        // テスト対象のメソッドを呼び出す
        $library = new googleBooksApiLibrary();
        $result = $library->getBookByIsbn('1234567890123');

        // 結果のアサーション
        $this->assertNull($result);
    }

    public function testSearchBooksNoIndustryIdentifiers()
    {
        // モックするレスポンスデータ
        $mockResponse = [
            'items' => [
                [
                    'volumeInfo' => [
                        'title' => 'Test Book',
                        'authors' => ['Author One', 'Author Two'],
                        'publishedDate' => '2020-01-01',
                        'publisher' => 'Test Publisher',
                        'imageLinks' => [
                            'thumbnail' => 'http://example.com/thumbnail.jpg'
                        ],
                        'infoLink' => 'http://example.com/info'
                    ]
                ]
            ]
        ];

        // HTTPリクエストのモック
        Http::fake([
            '*' => Http::response($mockResponse, 200)
        ]);

        // テスト対象のメソッドを呼び出す
        $library = new googleBooksApiLibrary();
        $result = $library->searchBooks('test query');

        // 結果のアサーション
        $this->assertEmpty($result);
    }
}

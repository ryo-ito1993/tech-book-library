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

        Http::fake([
            '*' => Http::response($mockResponse, 200)
        ]);

        $library = new googleBooksApiLibrary();
        $result = $library->searchBooks('test query');

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
        Http::fake([
            '*' => Http::response('API Error', 500)
        ]);

        $library = new googleBooksApiLibrary();

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('API request failed: API Error');

        $library->searchBooks('test query');
    }

    public function testGetBookByIsbnSuccess()
    {
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

        Http::fake([
            '*' => Http::response($mockResponse, 200)
        ]);

        $library = new googleBooksApiLibrary();
        $result = $library->getBookByIsbn('1234567890123');

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
        Http::fake([
            '*' => Http::response(['items' => []], 200)
        ]);

        $library = new googleBooksApiLibrary();
        $result = $library->getBookByIsbn('1234567890123');

        $this->assertNull($result);
    }

    public function testSearchBooksNoIndustryIdentifiers()
    {
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

        Http::fake([
            '*' => Http::response($mockResponse, 200)
        ]);

        $library = new googleBooksApiLibrary();
        $result = $library->searchBooks('test query');

        $this->assertEmpty($result);
    }
}

<?php

namespace Tests\Feature\Library;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Library\calilApiLibrary;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class calilApiLibraryTest extends TestCase
{
    public function testGetLibrariesByGeocodeSuccess(): void
    {
        // モックするレスポンスデータ
        $mockResponse = [
            'libraries' => [
                [
                    'systemid' => 'test_systemid',
                    'systemname' => 'test_systemname',
                    'libid' => 'test_libid',
                    'libkey' => 'test_libkey',
                    'short' => 'test_short',
                    'formal' => 'test_formal',
                ]
            ]
        ];

        Http::fake([
            '*' => Http::response($mockResponse, 200)
        ]);

        $library = new calilApiLibrary();
        $result = $library->getLibrariesByGeocode(35.6895, 139.6917);

        $this->assertCount(1, $result['libraries']);
        $this->assertSame('test_systemid', $result['libraries'][0]['systemid']);
        $this->assertSame('test_systemname', $result['libraries'][0]['systemname']);
        $this->assertSame('test_libid', $result['libraries'][0]['libid']);
        $this->assertSame('test_libkey', $result['libraries'][0]['libkey']);
        $this->assertSame('test_short', $result['libraries'][0]['short']);
        $this->assertSame('test_formal', $result['libraries'][0]['formal']);
    }

    public function testGetLibrariesByGeocodeApiFailure(): void
    {
        Http::fake([
            '*' => Http::response('API Error', 500)
        ]);

        $library = new calilApiLibrary();

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('API request failed: API Error');

        $library->getLibrariesByGeocode(35.6895, 139.6917);
    }

    public function testCheckBookAvailabilityPolling(): void
    {
        $initialResponse = [
            'continue' => 1,
            'session' => 'test_session'
        ];

        $finalResponse = [
            'session' => 'test_session',
            'continue' => 0,
            'books' => [
                '1234567890' => [
                    'test_systemid' => [
                        'libkey' => [
                            'lib_name1' => '貸出中',
                            'lib_name2' => '貸出可'
                        ],
                        'reserveurl' => 'https://example.com',
                        'status' => 'Cache'
                    ]
                ]
            ]
        ];

        Http::fake([
            '*' => Http::sequence()
                ->push($initialResponse, 200)
                ->push($finalResponse, 200)
        ]);

        $library = new calilApiLibrary();
        $result = $library->checkBookAvailability('1234567890', 'test_systemid');

        $this->assertSame('貸出中', $result['books']['1234567890']['test_systemid']['libkey']['lib_name1']);
    }

    public function testGetLibrariesBySystemIdSuccess(): void
    {
        $mockResponse = [
            'libraries' => [
                [
                    'systemid' => 'test_systemid',
                    'systemname' => 'test_systemname',
                    'libid' => 'test_libid',
                    'libkey' => 'test_libkey',
                    'short' => 'test_short',
                    'formal' => 'test_formal',
                ]
            ]
        ];

        Http::fake([
            '*' => Http::response($mockResponse, 200)
        ]);

        $library = new calilApiLibrary();
        $result = $library->getLibrariesBySystemId('test_systemid');

        $this->assertCount(1, $result['libraries']);
        $this->assertSame('test_systemid', $result['libraries'][0]['systemid']);
        $this->assertSame('test_systemname', $result['libraries'][0]['systemname']);
        $this->assertSame('test_libid', $result['libraries'][0]['libid']);
        $this->assertSame('test_libkey', $result['libraries'][0]['libkey']);
        $this->assertSame('test_short', $result['libraries'][0]['short']);
        $this->assertSame('test_formal', $result['libraries'][0]['formal']);
    }

    public function testGetLibrariesByPrefCitySuccess(): void
    {
        $mockResponse = [
            'libraries' => [
                [
                    'systemid' => 'test_systemid',
                    'systemname' => 'test_systemname',
                    'libid' => 'test_libid',
                    'libkey' => 'test_libkey',
                    'short' => 'test_short',
                    'formal' => 'test_formal',
                ]
            ]
        ];

        Http::fake([
            '*' => Http::response($mockResponse, 200)
        ]);

        $library = new calilApiLibrary();
        $result = $library->getLibrariesByPrefCity('Tokyo', 'Shibuya');

        $this->assertCount(1, $result['libraries']);
        $this->assertSame('test_systemid', $result['libraries'][0]['systemid']);
        $this->assertSame('test_systemname', $result['libraries'][0]['systemname']);
        $this->assertSame('test_libid', $result['libraries'][0]['libid']);
        $this->assertSame('test_libkey', $result['libraries'][0]['libkey']);
        $this->assertSame('test_short', $result['libraries'][0]['short']);
        $this->assertSame('test_formal', $result['libraries'][0]['formal']);
    }
}

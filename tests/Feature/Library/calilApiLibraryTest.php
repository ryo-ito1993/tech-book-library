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
    public function testGetLibrariesByGeocodeSuccess()
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
        $this->assertEquals('test_systemid', $result['libraries'][0]['systemid']);
        $this->assertEquals('test_systemname', $result['libraries'][0]['systemname']);
        $this->assertEquals('test_libid', $result['libraries'][0]['libid']);
        $this->assertEquals('test_libkey', $result['libraries'][0]['libkey']);
        $this->assertEquals('test_short', $result['libraries'][0]['short']);
        $this->assertEquals('test_formal', $result['libraries'][0]['formal']);
    }

    public function testGetLibrariesByGeocodeApiFailure()
    {
        Http::fake([
            '*' => Http::response('API Error', 500)
        ]);

        $library = new calilApiLibrary();

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('API request failed: API Error');

        $library->getLibrariesByGeocode(35.6895, 139.6917);
    }

    public function testCheckBookAvailabilityPolling()
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

        $this->assertEquals('貸出中', $result['books']['1234567890']['test_systemid']['libkey']['lib_name1']);
    }

    public function testGetLibrariesBySystemIdSuccess()
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
        $this->assertEquals('test_systemid', $result['libraries'][0]['systemid']);
        $this->assertEquals('test_systemname', $result['libraries'][0]['systemname']);
        $this->assertEquals('test_libid', $result['libraries'][0]['libid']);
        $this->assertEquals('test_libkey', $result['libraries'][0]['libkey']);
        $this->assertEquals('test_short', $result['libraries'][0]['short']);
        $this->assertEquals('test_formal', $result['libraries'][0]['formal']);
    }

    public function testGetLibrariesByPrefCitySuccess()
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
        $this->assertEquals('test_systemid', $result['libraries'][0]['systemid']);
        $this->assertEquals('test_systemname', $result['libraries'][0]['systemname']);
        $this->assertEquals('test_libid', $result['libraries'][0]['libid']);
        $this->assertEquals('test_libkey', $result['libraries'][0]['libkey']);
        $this->assertEquals('test_short', $result['libraries'][0]['short']);
        $this->assertEquals('test_formal', $result['libraries'][0]['formal']);
    }
}

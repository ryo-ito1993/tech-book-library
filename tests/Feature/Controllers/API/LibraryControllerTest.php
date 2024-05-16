<?php

namespace Tests\Feature\Controllers\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Library\calilApiLibrary;
use App\Models\City;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LibraryControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @var calilApiLibrary
     */
    protected $calilApiLibrary;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calilApiLibrary = $this->mock(calilApiLibrary::class);
    }

    public function testGetLibrariesByLocation(): void
    {
        $latitude = 35.6895;
        $longitude = 139.6917;

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

        $this->calilApiLibrary
            ->shouldReceive('getLibrariesByGeocode')
            ->with($latitude, $longitude)
            ->once()
            ->andReturn($mockResponse);

        $response = $this->postJson(route('api.libraries.getLibrariesByLocation'), [
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);

        $response->assertStatus(200);
        $response->assertJson($mockResponse);
    }

    public function testGetLibrariesByLocationHttpException(): void
    {
        $latitude = 35.6895;
        $longitude = 139.6917;

        $this->calilApiLibrary
            ->shouldReceive('getLibrariesByGeocode')
            ->with($latitude, $longitude)
            ->once()
            ->andThrow(new HttpException(400, 'API request failed'));

        $response = $this->postJson(route('api.libraries.getLibrariesByLocation'), [
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'error' => 'API request failed',
                'message' => 'API request failed'
            ]);
    }

    public function testGetLibrariesByLocationException(): void
    {
        $latitude = 35.6895;
        $longitude = 139.6917;

        $this->calilApiLibrary
            ->shouldReceive('getLibrariesByGeocode')
            ->with($latitude, $longitude)
            ->once()
            ->andThrow(new \Exception('An error occurred'));

        $response = $this->postJson(route('api.libraries.getLibrariesByLocation'), [
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);

        $response->assertStatus(500)
            ->assertJson([
                'error' => 'An error occurred',
                'message' => 'An error occurred'
            ]);
    }

    public function testGetLibrariesByPrefCity(): void
    {
        $pref = '東京都';
        $city = '千代田区';

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

        $this->calilApiLibrary
            ->shouldReceive('getLibrariesByPrefCity')
            ->with($pref, $city)
            ->once()
            ->andReturn($mockResponse);

        $response = $this->postJson(route('api.libraries.getLibrariesByPrefCity'), [
            'pref' => $pref,
            'city' => $city,
        ]);

        $response->assertStatus(200);
        $response->assertJson($mockResponse);
    }

    public function testGetLibrariesByPrefCityHttpException(): void
    {
        $pref = '東京都';
        $city = '千代田区';

        $this->calilApiLibrary
            ->shouldReceive('getLibrariesByPrefCity')
            ->with($pref, $city)
            ->once()
            ->andThrow(new HttpException(400, 'API request failed'));

        $response = $this->postJson(route('api.libraries.getLibrariesByPrefCity'), [
            'pref' => $pref,
            'city' => $city,
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'error' => 'API request failed',
                'message' => 'API request failed'
            ]);
    }

    public function testGetLibrariesByPrefCityException(): void
    {
        $pref = '東京都';
        $city = '千代田区';

        $this->calilApiLibrary
            ->shouldReceive('getLibrariesByPrefCity')
            ->with($pref, $city)
            ->once()
            ->andThrow(new \Exception('An error occurred'));

        $response = $this->postJson(route('api.libraries.getLibrariesByPrefCity'), [
            'pref' => $pref,
            'city' => $city,
        ]);

        $response->assertStatus(500)
            ->assertJson([
                'error' => 'An error occurred',
                'message' => 'An error occurred'
            ]);
    }

    public function testGetCitiesByPrefecture(): void
    {
        City::factory()->count(2)->create();
        $city = City::first();

        $prefectureId = $city->prefecture_id;

        $response = $this->getJson(route('api.libraries.getCitiesByPrefecture', ['prefectureId' => $prefectureId]));

        $response->assertStatus(200);
        $response->assertJsonFragment($city->toArray());
    }

    public function testGetBookAvailabilitySuccess(): void
    {
        $isbn = '9781234567897';
        $systemId = 'Tokyo_Shinjuku';
        $mockResponse = [
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

        $this->calilApiLibrary
            ->shouldReceive('checkBookAvailability')
            ->with($isbn, $systemId)
            ->once()
            ->andReturn($mockResponse);

        $response = $this->postJson(route('api.libraries.getBookAvailability'), [
            'isbn' => $isbn,
            'systemId' => $systemId,
        ]);

        $response->assertStatus(200)
            ->assertJson($mockResponse);
    }

    public function testGetBookAvailabilityException(): void
    {
        $isbn = '9781234567897';
        $systemId = 'Tokyo_Shinjuku';

        $this->calilApiLibrary
            ->shouldReceive('checkBookAvailability')
            ->with($isbn, $systemId)
            ->once()
            ->andThrow(new \Exception('An error occurred'));

        $response = $this->postJson(route('api.libraries.getBookAvailability'), [
            'isbn' => $isbn,
            'systemId' => $systemId,
        ]);

        $response->assertStatus(500)
            ->assertJson([
                'error' => 'An error occurred',
                'message' => 'An error occurred'
            ]);
    }

    public function testGetBookAvailabilityHttpException(): void
    {
        $isbn = '9781234567897';
        $systemId = 'Tokyo_Shinjuku';

        $this->calilApiLibrary
            ->shouldReceive('checkBookAvailability')
            ->with($isbn, $systemId)
            ->once()
            ->andThrow(new HttpException(400, 'API request failed'));

        $response = $this->postJson(route('api.libraries.getBookAvailability'), [
            'isbn' => $isbn,
            'systemId' => $systemId,
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'error' => 'API request failed',
                'message' => 'API request failed'
            ]);
    }
}

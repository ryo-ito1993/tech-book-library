<?php

namespace App\Library;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class calilApiLibrary
{
    protected $apiBaseUrl;

    protected $appKey;

    public function __construct()
    {
        $this->appKey = config('services.calil.app_key');
        $this->apiBaseUrl = config('services.calil.api_base_url');
    }

    protected function makeRequest(string $endpoint, array $params): array
    {
        $params['appkey'] = $this->appKey;
        $params['format'] = 'json';
        $params['callback'] = 'no';

        $response = Http::get($this->apiBaseUrl . $endpoint, $params);

        if ($response->ok()) {
            return $response->json();
        }
            $status = $response->status();
            $error = $response->body();
            Log::error("API Request Failed", [
                'status_code' => $status,
                'error' => $error,
            ]);
            throw new HttpException($status, "API request failed: " . $error);

    }

    public function getLibrariesByGeocode(float $latitude, float $longitude, int $limit = 20): array
    {
        $params = [
            'geocode' => "{$longitude},{$latitude}",
            'limit' => $limit,
        ];

        return $this->makeRequest('library', $params);
    }

    public function getLibrariesBySystemId(string $systemId): array
    {
        $params = [
            'systemid' => $systemId,
        ];

        return $this->makeRequest('library', $params);
    }

    public function getLibrariesByPrefCity(string $pref, string $city, int $limit = 30): array
    {
        $params = [
            'pref' => $pref,
            'city' => $city,
            'limit' => $limit,
        ];

        return $this->makeRequest('library', $params);
    }

    public function checkBookAvailability(string $isbn, string $systemId, ?string $session = null): array
    {
        $params = [
            'isbn' => $isbn,
            'systemid' => $systemId
        ];

        if ($session) {
            $params['session'] = $session;
        }

        $response = $this->makeRequest('check', $params);

        // continueが1の場合はポーリングを続ける
        if ($response['continue'] === 1) {
            sleep(2);
            return $this->checkBookAvailability($isbn, $systemId, $response['session']);
        }

        return $response;
    }
}

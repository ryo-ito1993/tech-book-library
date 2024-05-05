<?php

namespace App\Library;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class calilApiLibrary
{
    protected $apiBaseUrl = 'https://api.calil.jp/';

    protected $appKey;

    public function __construct()
    {
        $this->appKey = env('CALIL_APP_KEY');
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
}

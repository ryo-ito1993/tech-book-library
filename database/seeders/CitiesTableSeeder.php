<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\City;
use App\Models\Prefecture;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $apiKey = env('RESAS_API_KEY');
        $url = 'https://opendata.resas-portal.go.jp/api/v1/cities';

        $prefectures = Prefecture::all();

        foreach ($prefectures as $prefecture) {
            $response = Http::withHeaders([
                'X-API-KEY' => $apiKey
            ])->get($url, [
                'prefCode' => $prefecture->pref_code,
            ]);

            if ($response->successful()) {
                $cities = $response->json()['result'];

                foreach ($cities as $city) {
                    City::updateOrCreate(
                        ['city_code' => $city['cityCode']],
                        [
                            'prefecture_id' => $prefecture->id,
                            'name' => $city['cityName'],
                        ]
                    );
                }
            } else {
                Log::error('Failed to fetch cities from RESAS API', [
                    'prefCode' => $prefecture->pref_code,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }
        }
    }
}

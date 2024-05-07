<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Prefecture;

class PrefecturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $apiKey = config('services.resas.api_key');
        $url = config('services.resas.api_base_url') . 'prefectures';

        $response = Http::withHeaders([
            'X-API-KEY' => $apiKey
        ])->get($url);

        if ($response->successful()) {
            $prefectures = $response->json()['result'];

            foreach ($prefectures as $prefecture) {
                Prefecture::updateOrCreate(
                    ['pref_code' => $prefecture['prefCode']],
                    ['name' => $prefecture['prefName']]
                );
            }
        } else {
            Log::error('Failed to fetch prefectures from RESAS API', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
        }
    }
}

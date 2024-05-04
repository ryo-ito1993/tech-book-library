<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Library;

class LibraryController extends Controller
{
    public function getLibraries(Request $request)
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        $response = Http::get("https://api.calil.jp/library", [
            'appkey' => env('CALIL_APP_KEY'),
            'geocode' => "{$longitude},{$latitude}",
            'limit' => 20,
            'format' => 'json',
            'callback' => 'no',
        ]);

        if ($response->successful()) {
            $jsonResponse = $response->json();

            $libraries = $jsonResponse;
            $libraryList = array_values($libraries);

            return response()->json($libraryList);
        }  
            Log::error("API Request Failed", [
                'status_code' => $response->status(),
                'error' => $response->body()
            ]);

            return response()->json(['error' => 'API request failed'], $response->status());
        
    }
}

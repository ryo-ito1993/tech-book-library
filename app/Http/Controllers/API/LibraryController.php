<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\City;
use App\Library\calilApiLibrary;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Exception;
use Illuminate\Http\JsonResponse;

class LibraryController extends Controller
{
    public function __construct(
        protected CalilApiLibrary $calilApiLibrary,
    ) {
    }

    public function getLibrariesByLocation(Request $request): JsonResponse
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        try {
            $libraries = $this->calilApiLibrary->getLibrariesByGeocode($latitude, $longitude);
            return response()->json($libraries);
        } catch (HttpException $e) {
            return response()->json(['error' => 'API request failed', 'message' => $e->getMessage()], $e->getStatusCode());
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred', 'message' => $e->getMessage()], 500);
        }
    }

    public function getLibrariesByPrefCity(Request $request): JsonResponse
    {
        $pref = $request->input('pref');
        $city = $request->input('city');
        try {
            $libraries = $this->calilApiLibrary->getLibrariesByPrefCity($pref, $city);
            return response()->json($libraries);
        } catch (HttpException $e) {
            return response()->json(['error' => 'API request failed', 'message' => $e->getMessage()], $e->getStatusCode());
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred', 'message' => $e->getMessage()], 500);
        }
    }

    public function getCitiesByPrefecture(int $prefectureId): JsonResponse
    {
        $cities = City::where('prefecture_id', $prefectureId)->get();
        return response()->json($cities);
    }

    public function getBookAvailability(Request $request): JsonResponse
    {
        $isbn = $request->input('isbn');
        $systemId = $request->input('systemId');
        try {
            $bookAvailable = $this->calilApiLibrary->checkBookAvailability($isbn, $systemId);
            return response()->json($bookAvailable);
        } catch (HttpException $e) {
            return response()->json(['error' => 'API request failed', 'message' => $e->getMessage()], $e->getStatusCode());
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred', 'message' => $e->getMessage()], 500);
        }
    }
}

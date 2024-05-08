<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Library;
use App\Models\City;
use App\Library\CalilApiLibrary;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Exception;

class LibraryController extends Controller
{
    protected $calilApiLibrary;

    public function __construct(CalilApiLibrary $calilApiLibrary)
    {
        $this->calilApiLibrary = $calilApiLibrary;
    }

    public function getLibrariesByLocation(Request $request)
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

    public function getLibrariesByPrefCity(Request $request)
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

    public function getCitiesByPrefecture(int $prefectureId)
    {
        $cities = City::where('prefecture_id', $prefectureId)->get();
        return response()->json($cities);
    }

    public function getBookAvailability(Request $request)
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

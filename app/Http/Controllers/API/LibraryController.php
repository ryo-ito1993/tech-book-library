<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Library;
use App\Library\CalilApiLibrary;
use Illuminate\Http\Client\HttpException;
use Exception;

class LibraryController extends Controller
{
    protected $calilApiLibrary;

    public function __construct(CalilApiLibrary $calilApiLibrary)
    {
        $this->calilApiLibrary = $calilApiLibrary;
    }

    public function getLibraries(Request $request)
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
}

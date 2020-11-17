<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PlaceService;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    private $placeService;

    public function __construct(PlaceService $placeService)
    {
        $this->placeService = $placeService;
    }

    public function index()
    {
        $places = $this->placeService->getPlaces(request()->all());
//        $total = $this->placeService->getTotalPlaces(request()->all());
        $total = 1;

        return $this->responseSuccess(['places' => $places, 'total' => $total]);
    }

    public function getRootHospitals()
    {
        $hospitals = $this->placeService->getRootHospitals();

        return $this->responseSuccess($hospitals);
    }

    public function store()
    {
        $this->placeService->createPlace(request()->all());

        return $this->responseSuccess();
    }

}

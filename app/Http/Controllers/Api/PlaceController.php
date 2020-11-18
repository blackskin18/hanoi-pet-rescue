<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PlaceService;
use App\Models\Place;
use App\Services\UserService;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    private $placeService;
    private $userService;

    public function __construct(PlaceService $placeService, UserService $userService)
    {
        $this->middleware('jwt-authen');
        $this->placeService = $placeService;
        $this->userService = $userService;
    }

    public function index()
    {
        if(request()->get('type') == Place::FOSTER) {
            $places = $this->userService->getFoster(request()->all());
            $total = $this->userService->getTotalFoster(request()->all());
        } else {
            $places = $this->placeService->getPlaces(request()->all());
            $total = $this->placeService->getTotalPlaces(request()->all());
        }

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

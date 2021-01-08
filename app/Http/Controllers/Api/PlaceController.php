<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EditPlace;
use App\Services\PlaceService;
use App\Http\Requests\StorePlace;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        $places = $this->placeService->getPlaces(request()->all());
        $total = $this->placeService->getTotalPlaces(request()->all());

        return $this->responseSuccess(['places' => $places, 'total' => $total]);
    }

    public function getRootHospitals()
    {
        $hospitals = $this->placeService->getRootHospitals();

        return $this->responseSuccess($hospitals);
    }

    public function getHospitals()
    {
        $hospitals = $this->placeService->getHospitals(request()->all());

        return $this->responseSuccess($hospitals);
    }

    public function store(StorePlace $request)
    {
        $this->placeService->createPlace(request()->all());

        return $this->responseSuccess();
    }

    public function update(EditPlace $request, $placeId) {
        $this->placeService->updatePlace(request()->all(), $placeId);

        return $this->responseSuccess();
    }

    public function show($placeId)
    {
        $place = $this->placeService->getPlaceBtId($placeId);

        return $this->responseSuccess($place);
    }

    public function destroy($placeId) {
        try {
            $result = $this->placeService->deleteById($placeId);

            return $result === true ? $this->responseSuccess() : $this->responseError($result);
        } catch (\Exception $e) {
            Log::error($e);

            return $this->responseError('Delete Error');
        }
    }
}

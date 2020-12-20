<?php

namespace App\Http\Controllers\Api;

use App\AnimalHospital;
use App\Exports\ReportExport;
use App\Hospital;
use App\Http\Controllers\Controller;
use App\Models\Animal;
use App\Services\AnimalService;
use App\Services\PlaceHistoryService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreAnimal;
use App\Http\Requests\EditAnimal;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PlaceHistoryController extends Controller
{
    const HOSPITAL = 1;
    const COMMON_HOME = 2;
    const FOSTER = 3;
    const OWNER = 4;

    private $placeHistoryService;

    public function __construct(PlaceHistoryService $placeHistoryService)
    {
        //$this->middleware('jwt-authen');
        $this->placeHistoryService = $placeHistoryService;
    }

    public function show($placeId)
    {
        $data = $this->placeHistoryService->getHistoryByPlaceId($placeId);

        return $this->responseSuccess($data);
    }

}

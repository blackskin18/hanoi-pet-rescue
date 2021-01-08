<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\PlaceService;
use App\Models\Place;
use App\Services\HistoryService;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    private $historyService;

    public function __construct(HistoryService $historyService)
    {
//        $this->middleware('jwt-authen');
        $this->historyService = $historyService;
    }

    public function index()
    {
        $histories = $this->historyService->getHistories(request()->all());
        $total = $this->historyService->getCountHistory(request()->all());

        return $this->responseSuccess(['histories' => $histories, 'total' => $total]);
    }
}

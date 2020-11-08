<?php

namespace App\Http\Controllers\Api;

use App\Models\Status;
use App\Http\Controllers\Controller;

class StatusController extends Controller
{

    public function index()
    {
        $status = Status::get();
        return $this->responseSuccess($status);
    }
}

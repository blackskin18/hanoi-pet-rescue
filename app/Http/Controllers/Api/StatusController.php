<?php

namespace App\Http\Controllers\Api;

use App\Models\Status;
use App\Http\Controllers\Controller;

class StatusController extends Controller
{
    public function __construct()
    {
//        $this->middleware('jwt-authen');
    }


    public function index()
    {
        $status = Status::get();
        return $this->responseSuccess($status);
    }
}

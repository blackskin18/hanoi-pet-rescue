<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt-authen');
    }

    public function index()
    {
        $roles = Role::get();

        return $this->responseSuccess($roles);
    }
}

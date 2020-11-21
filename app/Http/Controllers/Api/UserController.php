<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\PlaceService;
use App\Models\Place;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    private $placeService;

    private $userService;

    public function __construct(PlaceService $placeService, UserService $userService)
    {
        //$this->middleware('jwt-authen');
        $this->placeService = $placeService;
        $this->userService = $userService;
    }

    public function index()
    {
        $users = $this->userService->getUsers(request()->all());
        $total = $this->userService->getTotalUsers(request()->all());

        return $this->responseSuccess(['users' => $users, 'total' => $total]);
    }

    public function getRootHospitals()
    {
        $hospitals = $this->placeService->getRootHospitals();

        return $this->responseSuccess($hospitals);
    }

    public function store()
    {
        $this->userService->createUser(request()->all());

        return $this->responseSuccess();
    }

    public function show($userId)
    {
        $user = $this->userService->getUserById($userId);

        return $this->responseSuccess($user);
    }

    public function destroy($userId)
    {
        try {
            $result = $this->userService->deleteById($userId);

            return $result === true ? $this->responseSuccess() : $this->responseError($result);
        } catch (\Exception $e) {
            Log::error($e);

            return $this->responseError('Delete Error');
        }
    }
}

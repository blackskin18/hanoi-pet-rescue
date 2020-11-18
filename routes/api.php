<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AnimalController;
use App\Http\Controllers\Api\StatusController;
use App\Http\Controllers\Api\PlaceController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/places/root-hospitals', [PlaceController::class, 'getRootHospitals']);
Route::post('/auth/login', [AuthController::class, 'login'])->name('login');
Route::post('/auth/verify-token', [AuthController::class, 'verify']);

Route::apiResource('/cases', AnimalController::class);
Route::apiResource('/statuses', StatusController::class);
Route::apiResource('/places', PlaceController::class);

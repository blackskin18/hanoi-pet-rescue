<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AnimalController;
use App\Http\Controllers\Api\StatusController;
use App\Http\Controllers\Api\PlaceController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PlaceHistoryController;
use App\Http\Controllers\Api\HistoryController;

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

Route::get('/animal-test/{id}', [AnimalController::class, 'test']);
Route::get('/code-to-create', [AnimalController::class, 'getLastCode']);
Route::get('/export-report', [AnimalController::class, 'exportReport']);

Route::get('/histories', [HistoryController::class, 'index']);

Route::get('/places/root-hospitals', [PlaceController::class, 'getRootHospitals']);
Route::get('/places/hospitals', [PlaceController::class, 'getHospitals']);
Route::get('/cases/report', [AnimalController::class, 'getReport']);
Route::post('/auth/login', [AuthController::class, 'login'])->name('login');
Route::post('/auth/verify-token', [AuthController::class, 'verify']);
Route::get('/users/get-all', [UserController::class, 'getAllUsers']);

Route::apiResource('/cases', AnimalController::class);
Route::apiResource('/statuses', StatusController::class);
Route::apiResource('/places', PlaceController::class);
Route::apiResource('/roles', RoleController::class);
Route::apiResource('/users', UserController::class);
Route::apiResource('/place-histories', PlaceHistoryController::class);

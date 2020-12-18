<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AnimalService;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreAnimal;
use App\Http\Requests\EditAnimal;
use Illuminate\Support\Facades\DB;


class AnimalController extends Controller
{
    private $animalService;

    public function __construct(AnimalService $animalService)
    {
        //$this->middleware('jwt-authen');
        $this->animalService = $animalService;
    }

    public function index()
    {
        $animals = $this->animalService->getListAnimalsByType(request()->all());
        $total = $this->animalService->getTotalAnimal(request()->all());

        return $this->responseSuccess(['cases' => $animals, 'total' => $total]);
    }

    public function store(StoreAnimal $request)
    {
        $this->animalService->createAnimal(request()->all());

        return $this->responseSuccess();
    }

    public function show($animalId)
    {
        $animal = $this->animalService->getAnimalById($animalId);

        return $this->responseSuccess($animal);
    }

    public function destroy($animalId)
    {
        try {
            $this->animalService->deleteById($animalId);

            return $this->responseSuccess($animalId);
        } catch (\Exception $e) {
            Log::error($e);

            return $this->responseError('Xóa Case th ất bại, vui lòng liên hệ kỹ thuật !!');
        }
    }

    public function update(EditAnimal $request, $id)
    {
        $this->animalService->editAnimal(request()->all(), $id);

        return $this->responseSuccess();
    }

    public function getReport()
    {
        $reportData = $this->animalService->getReportData(request()->get('start_time'), request()->get('end_time'));
        return $this->responseSuccess($reportData);
    }

    public function test($animalId)
    {
        $animals = DB::table('animals')
            ->where('age', null)->get();

        foreach($animals as $animal) {
            DB::table('animals')
                ->where('id', $animal->id)->update(['date_of_birth' => '2020-12-18']);
        }

        dd($animals);die;
    }
}

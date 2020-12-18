<?php

namespace App\Http\Controllers\Api;

use App\AnimalHospital;
use App\Http\Controllers\Controller;
use App\Services\AnimalService;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreAnimal;
use App\Http\Requests\EditAnimal;
use Illuminate\Support\Facades\DB;


class AnimalController extends Controller
{
    const HOSPITAL = 1;
    const COMMON_HOME = 2;
    const FOSTER = 3;
    const OWNER = 4;

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
        // update date_of_birth

//        $animals = DB::table('animals')
//            ->where('age', null)->get();
//
//        foreach($animals as $animal) {
//            DB::table('animals')
//                ->where('id', $animal->id)->update(['date_of_birth' => '2020-12-18']);
//        }

//        insert place
//        hospitals
//        $hospitals = DB::table('hospitals')->get();
//        foreach ($hospitals as $hospital) {
//            DB::table('places')->insert([
//                'type' => self::HOSPITAL,
//                'phone' => $hospital->phone,
//                'address' => $hospital->address,
//                'note' => $hospital->note,
//                'name' => $hospital->name,
//                'old_id' => $hospital->id,
//            ]);
//        }

//        common home
//        DB::table('places')->insert([
//                'type' => self::COMMON_HOME,
//                'phone' => '',
//                'address' => '',
//                'note' => 'Nhà chung từ hệ thống cũ',
//                'name' => 'Nhà chung',
//            ]);

//        foster
//        $fosters = DB::table('animal_fosters')
//            ->groupByRaw('foster_id')
//            ->leftJoin('users', 'animal_fosters.foster_id', '=', 'users.id')
//            ->get();
//
//        foreach ($fosters as $foster) {
//            DB::table('places')->insert([
//                'type' => self::FOSTER,
//                'phone' => $foster->phone,
//                'address' => $foster->address,
//                'note' => $foster->note,
//                'name' => $foster->name ?? '',
//                'old_id' => $foster->id,
//            ]);
//        }


//        update animal place
        $animals =  $fosters = DB::table('animals')->get();
        $places = 0;
        foreach ($animals as $animal) {
            $place = DB::table('animal_hospitals')->where('animal_id', $animal->id)->orderBy('created_at', 'desc')->take(1)->get();
//            dd($place);
            if($place)
            $places++;
//            $place[0]->hospital;
//            $histories[$key]['old_value_place'] =  $place[0];
        }
        dd($places);die;

        dd($animals);

    }
}

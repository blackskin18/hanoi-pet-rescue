<?php

namespace App\Http\Controllers\Api;

use App\AnimalHospital;
use App\Exports\ReportExport;
use App\Hospital;
use App\Http\Controllers\Controller;
use App\Services\AnimalService;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreAnimal;
use App\Http\Requests\EditAnimal;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;


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
        $type = 0;
        if(!request()->get('start_time')) $type = 1;
        $reportData = $this->animalService->getReportData(request()->get('start_time'), request()->get('end_time'));



        return Excel::download(new ReportExport($reportData, $type, request()->get('end_time')),
            'invoices.xlsx');
    }

    //public function test($animalId)
    //{
        // update date_of_birth

        //$animals = DB::table('animals')
        //    ->where('age', null)->get();
        //
        //foreach($animals as $animal) {
        //    DB::table('animals')
        //        ->where('id', $animal->id)->update(['date_of_birth' => '2020-12-18']);
        //}

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

        //common home
        //DB::table('places')->insert([
        //        'type' => self::COMMON_HOME,
        //        'phone' => '',
        //        'address' => '',
        //        'note' => 'Nhà chung từ hệ thống cũ',
        //        'name' => 'Nhà chung',
        //    ]);

        //Nhà Chung Hoài Đức
        //DB::table('places')->insert([
        //    'type' => self::COMMON_HOME,
        //    'phone' => '',
        //    'address' => '',
        //    'note' => 'Nhà chung từ hệ thống cũ',
        //    'name' => 'Nhà chung Hoài Đức',
        //]);

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
//        $animals = DB::table('animals')->where('place', 'hospital')->get();
//        foreach ($animals as $animal) {
//            $place = DB::table('animal_hospitals')->where('animal_id', $animal->id)->orderBy('created_at', 'desc')->take(1)->get();
//            if(isset($place[0])) {
//                $newPlace = DB::table('places')->where('old_id', $place[0]->hospital_id)->where('type', self::HOSPITAL)->first();
//                DB::table('animals')
//                    ->where('id', $animal->id)
//                    ->where('place', 'hospital')
//                    ->update(['place_id' => $newPlace->id,
//                              'place_type' => self::HOSPITAL]);
//            }
//        }


        //$animals = DB::table('animals')->where('place', 'volunteer')->get();
        //foreach ($animals as $animal) {
        //    $place = DB::table('animal_fosters')->where('animal_id', $animal->id)->orderBy('created_at', 'desc')->take(1)->get();
        //    if(isset($place[0])) {
        //        $newPlace = DB::table('places')->where('old_id', $place[0]->foster_id)->where('type', self::FOSTER)->first();
        //        DB::table('animals')
        //            ->where('id', $animal->id)
        //            ->where('place', 'volunteer')
        //            ->update(['place_id' => $newPlace->id,
        //                  'place_type' => self::FOSTER]);
        //
        //    }
        //}


        //DB::table('animals')->where('place', 'Nhà Chung Hoài Đức')
        //    ->update(['place_id' => 102,
        //          'place_type' => self::COMMON_HOME]);
        //DB::table('animals')->where('place', 'commonHome')
        //    ->update(['place_id' => 23,
        //              'place_type' => self::COMMON_HOME]);

    //}
}

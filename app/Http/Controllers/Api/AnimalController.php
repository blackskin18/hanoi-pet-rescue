<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AnimalService;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreAnimal;

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


    public function getReport()
    {
        $reportData = $this->animalService->getReportData(request()->get('start_time'), request()->get('end_time'));
        return $this->responseSuccess($reportData);
    }

    public function test($animalId)
    {
        //$place = Place::with('animal')->find(1);
        //return $place;
        var_dump(1);die;

        //DB::table('animals')
        //    ->update(['place_type' => DB::raw("(CASE
        //    WHEN `place`='commonHome' THEN 2
        //    WHEN `place`='volunteer' THEN 3
        //    WHEN `place`='hospital' THEN 1
        //    ELSE null END)")]);

        //try {
        //    $this->animalService->deleteById($animalId);
        //
        //    return $this->responseSuccess();
        //} catch (\Exception $e) {
        //    Log::error($e);
        //
        //    return $this->responseError('Delete Error');
        //}
    }


    //public function getAnimalInfo($code)
    //{
    //    $animal = Animal::where('code', $code)->first();
    //
    //    $animalId = $animal->id;
    //
    //    $timeReceive = $animal->time_receive;
    //    $timeReceiveInt = strtotime( $timeReceive );
    //    $animal->time_receive = date( 'd-m-Y', $timeReceiveInt );
    //
    //    $images = Animal::find($animalId)->animalImage()->get();
    //    $allStatus = Status::all();
    //    $animalFosters = AnimalFoster::where('animal_id', $animalId)->get();
    //    $userId = Auth::user()->id;
    //    $userRole = UserRole::where('user_id', $userId)->get();
    //    $histories = History::where('animal_id', $animalId)->orderBy('created_at','desc')->get();
    //
    //    foreach ($histories as $key => $value) {
    //
    //        $histories[$key]->user;
    //
    //        if($histories[$key]->attribute == 'place'){
    //
    //            if($histories[$key]->old_value && $histories[$key]->old_value == 'hospital'){
    //
    //                $place = AnimalHospital::where('animal_id', $histories[$key]->animal_id)->orderBy('created_at', 'desc')->take(1)->get();
    //
    //                $place[0]->hospital;
    //
    //                $histories[$key]['old_value_place'] =  $place[0];
    //
    //            } elseif($histories[$key]->old_value == 'volunteer'){
    //
    //                $place = AnimalFoster::where('animal_id', $histories[$key]->animal_id)->orderBy('created_at', 'desc')->take(1)->get();
    //
    //                $place[0]->foster;
    //
    //                $histories[$key]['old_value_place'] =  $place[0];
    //
    //            }
    //
    //
    //
    //            if($histories[$key]->new_value && $histories[$key]->new_value == 'hospital'){
    //
    //                $place = AnimalHospital::where('animal_id', $histories[$key]->animal_id)->orderBy('created_at', 'desc')->take(1)->get();
    //
    //                $place[0]->hospital;
    //
    //                $histories[$key]['new_value_place'] =  $place[0];
    //
    //            } elseif($histories[$key]->new_value == 'volunteer'){
    //
    //                $place = AnimalFoster::where('animal_id', $histories[$key]->animal_id)->orderBy('created_at', 'desc')->take(1)->get();
    //
    //                $place[0]->foster;
    //
    //                $histories[$key]['new_value_place'] =  $place[0];
    //
    //            }
    //        }
    //
    //    }
    //
    //    $placeResult = null;
    //
    //    if($animal->place == 'hospital'){
    //
    //        $place = AnimalHospital::where('animal_id', $animalId)->orderBy('created_at', 'desc')->take(1)->get();
    //        if(isset($place[0])) {
    //            $placeResult = [$place[0]->hospital, $place[0] ];
    //        } else {
    //            $placeResult = null;
    //        }
    //
    //    } elseif($animal->place == 'volunteer'){
    //
    //        $place = AnimalFoster::where('animal_id', $animalId)->orderBy('created_at', 'desc')->take(1)->get();
    //        if(isset($place[0])) {
    //            $placeResult = [$place[0]->foster, $place[0] ];
    //        } else {
    //            $placeResult = null;
    //        }
    //    }
    //
    //
    //    // return $animal->time_receive;
    //
    //    return view('animal/detail_info')   ->with('animal',            $animal)
    //
    //                                        ->with('images',            $images)
    //
    //                                        ->with('histories',         $histories)
    //
    //                                        ->with('all_status',        $allStatus)
    //
    //                                        ->with('animal_fosters',    $animalFosters)
    //
    //                                        ->with('place',             $placeResult )
    //
    //                                        ->with('user_level',        $userRole[0]->role_id);
    //
    //}
    //
    //
    //
    //
    //
    //public function editCreateAt(Request $request, $animalId)
    //
    //{
    //
    //    $animal = Animal::find($animalId);
    //
    //
    //
    //    $history = new HistoryController;
    //
    //    $history->saveLog(Auth::User()->id, $animalId, 'create_at', $animal->created_at, $request->data, 'Sửa ngày nhận');
    //
    //    $animal->created_at = $request->data;
    //
    //    $animal->save();
    //
    //    return $animal->created_at;
    //
    //}
    //
    //
    //
    //
    //
    //public function editAddress(Request $request, $animalId)
    //
    //{
    //
    //    $animal = Animal::find($animalId);
    //
    //
    //
    //    $history = new HistoryController;
    //
    //    $history->saveLog(Auth::User()->id, $animalId, 'address', $animal->address, $request->data, 'Sửa địa chỉ');
    //
    //
    //
    //    $animal->address = $request->data;
    //
    //    $animal->save();
    //
    //    return $animal->address;
    //
    //}
    //
    //
    //public function editName(Request $request, $animalId)
    //{
    //    $animal = Animal::find($animalId);
    //    $history = new HistoryController;
    //    $history->saveLog(Auth::User()->id, $animalId, 'name', $animal->name, $request->data, 'Sửa Trường Hợp');
    //    $animal->name = $request->data;
    //    $animal->save();
    //    return nl2br($animal->name);
    //}
    //
    //public function editNote(Request $request, $animalId)
    //{
    //    $animal = Animal::find($animalId);
    //
    //    $history = new HistoryController;
    //    if ($animal->note != $request->data) {
    //        $history->saveLog(Auth::User()->id, $animalId, 'note', $animal->note, $request->data, 'Sửa Ghi Chú');
    //        $animal->note = $request->data;
    //        $animal->save();
    //    }
    //    return  nl2br($animal->note);
    //
    //}
    //
    //public function editType(Request $request, $animalId)
    //{
    //    $animal = Animal::find($animalId);
    //
    //    $history = new HistoryController;
    //    $history->saveLog(Auth::User()->id, $animalId, 'type', $animal->type, $request->data, 'Sửa loài');
    //
    //    $animal->type = $request->data;
    //    $animal->save();
    //    return $animal->type;
    //
    //}
    //
    //
    //
    //public function editStatus (Request $request, $animalId)
    //{
    //    $animal = Animal::find($animalId);
    //
    //    $history = new HistoryController;
    //    $statuses = Status::all();
    //    foreach ($statuses as $key => $status) {
    //        if($status->id == $animal->status){
    //            $oldValue = $status->name;
    //        }
    //        if($status->id == $request->data){
    //            $newValue = $status->name;
    //        }
    //    }
    //
    //    $history->saveLog(Auth::User()->id, $animalId, 'status', $oldValue, $newValue, 'Sửa trạng thái');
    //
    //
    //
    //    $animal->status = $request->data;
    //
    //    $animal->save();
    //
    //
    //
    //    return $newValue;
    //
    //}
    //
    //
    //
    //public function editDescription(Request $request, $animalId)
    //
    //{
    //
    //    $animal = Animal::find($animalId);
    //
    //
    //
    //    $history = new HistoryController;
    //
    //    $history->saveLog(Auth::User()->id, $animalId, 'description', $animal->description, $request->data, 'Sửa mô tả');
    //
    //
    //
    //    $animal->description = $request->data;
    //
    //    $animal->save();
    //
    //    return nl2br($animal->description);
    //
    //}
    //
    //
    //
    //public function editAge(Request $request, $animalId)
    //
    //{
    //
    //    $animal = Animal::find($animalId);
    //
    //
    //
    //    $history = new HistoryController;
    //
    //    $history->saveLog(Auth::User()->id, $animalId, 'age', $animal->age, $request->data, 'Sửa tuổi');
    //
    //
    //
    //    $animal->age = $request->data;
    //
    //    $animal->save();
    //
    //    return $animal->age;
    //
    //}
    //
    //
    //
    //
    //
    //public function editPlace(Request $request, $animalId)
    //
    //{
    //    $animal = Animal::find($animalId);
    //
    //    if($request->data == 'commonHome'){
    //
    //        $history = new HistoryController;
    //
    //        $history->saveLog(Auth::User()->id, $animalId, 'place', $animal->place, $request->data, 'Sửa địa điểm');
    //
    //
    //
    //        $animal->place = $request->data;
    //
    //        $animal->save();
    //
    //        return ['data'  => $request->data,
    //
    //                'obj'   => $request->obj,
    //
    //                'note'  => $request->note,
    //
    //                ];
    //
    //    }
    //
    //    elseif ($request->data == 'volunteer'){
    //
    //        $history = new HistoryController;
    //
    //        $history->saveLog(Auth::User()->id, $animalId, 'place', $animal->place, $request->data, 'Sửa địa điểm');
    //
    //
    //
    //        $animal->place = $request->data;
    //
    //        $animal->save();
    //
    //        $animalFosters = new AnimalFoster();
    //
    //        $animalFosters->animal_id = $animalId;
    //
    //        $animalFosters->foster_id = $request->obj;
    //
    //        $animalFosters->note = $request->note;
    //
    //        $animalFosters->save();
    //
    //
    //
    //        $volunteer = User::find($request->obj);
    //
    //
    //
    //        return ['data'  => $request->data,
    //
    //                'obj'   => $volunteer,
    //
    //                'note'  => $request->note,
    //
    //                ];
    //
    //    }
    //
    //    elseif ($request->data == 'hospital'){
    //
    //        $history = new HistoryController;
    //
    //        $history->saveLog(Auth::User()->id, $animalId, 'place', $animal->place, $request->data, 'Sửa địa điểm');
    //
    //
    //
    //        $animal->place = $request->data;
    //
    //        $animal->save();
    //
    //        $animalHospital = new AnimalHospital();
    //
    //        $animalHospital->animal_id = $animalId;
    //
    //        $animalHospital->hospital_id = $request->obj;
    //
    //        $animalHospital->note = $request->note;
    //
    //        $animalHospital->save();
    //
    //
    //
    //        $hospital = Hospital::find($request->obj);
    //
    //
    //
    //
    //
    //        return ['data'  => $request->data,
    //
    //                'obj'   => $hospital,
    //
    //                'note'  => $request->note,
    //
    //                ];
    //
    //    }
    //
    //    else if($request->data == 'other'){
    //
    //        $history = new HistoryController;
    //
    //        $history->saveLog(Auth::User()->id, $animalId, 'place', $animal->place, $request->note, 'Sửa địa điểm');
    //
    //
    //        $animal->place = $request->note;
    //
    //        $animal->save();
    //
    //        return ['data'  => $request->data,
    //
    //                'obj'   => $request->obj,
    //
    //                'note'  => $request->note,
    //
    //                ];
    //
    //    } else {
    //        $history = new HistoryController;
    //
    //        $history->saveLog(Auth::User()->id, $animalId, 'place', $animal->place, $request->data, 'Sửa địa điểm');
    //
    //
    //        $animal->place = $request->data;
    //
    //        $animal->save();
    //
    //        return ['data'  => $request->data,
    //
    //            'obj'   => $request->obj,
    //
    //            'note'  => $request->note,
    //
    //        ];
    //    }
    //
    //
    //
    //}
    //
    //
    //
    //
    //
    //public function postAddImage(UploadRequest $request)
    //{
    //    $animal = Animal::find($request->animal_id);
    //
    //    if($request->photos){
    //
    //        foreach ($request->photos as $photo) {
    //
    //
    //
    //            $filename = $photo->store('');
    //
    //            $file = $photo;
    //
    //            $file->move('/home/okv08coo9fkv/public_html/hanoipetrescue'.'/animal_image/'.$request->animal_id, $filename);
    //
    //
    //
    //            $animalImage = new AnimalImage;
    //
    //            $animalImage->animal_id = $request->animal_id;
    //
    //            $animalImage->created_by = Auth::user()->id;
    //
    //            $animalImage->file_name = $filename;
    //
    //            $animalImage->save();
    //
    //
    //
    //            $history = new HistoryController;
    //
    //            $history->saveLog(Auth::User()->id, $request->animal_id, 'image', null, $filename, 'Thêm Ảnh');
    //
    //        }
    //
    //        return Redirect::to('/animal/detail_info/'.$animal->code);
    //
    //    }
    //
    //    return Redirect::to('/animal/detail_info/'.$animal->code);
    //
    //}
    //
    //
    //
    //public function deleteImage($imageId)
    //
    //{
    //
    //    $history = new HistoryController;
    //
    //
    //
    //    $animalImage = AnimalImage::find($imageId);
    //
    //
    //
    //    $animalId = $animalImage->animal_id;
    //
    //    $fileName = $animalImage->file_name;
    //
    //
    //
    //    $history->saveLog(Auth::User()->id, $animalId, 'image', $fileName, null, 'Xóa Ảnh');
    //
    //
    //
    //    // File::Delete(public_path().'/animal_image/'.$animalId.'/'.$fileName);
    //
    //    $animalImage->delete();
    //
    //    return "true";
    //
    //}
    //
    //
    //
    //public function changeImage(Request $request, $imageId){
    //    $animalImage = AnimalImage::find($imageId);
    //    $animalId = $animalImage->animal_id;
    //    $file = $request->file('photo');
    //    $newFileName = $file->store('');
    //    $file->move('/home/okv08coo9fkv/public_html/hanoipetrescue'.'/animal_image/'.$animalId, $newFileName);
    //    $fileName = $animalImage->file_name;
    //    $animalImage->file_name = $newFileName;
    //    $animalImage->save();
    //    $history = new HistoryController;
    //
    //    $history->saveLog(Auth::User()->id, $animalId, 'image', $fileName, $newFileName, 'Thay Đổi Ảnh');
    //
    //
    //
    //    return "true";
    //
    //}
    //
    //
    //
    //public function getSummaryDetail($code){
    //
    //    $animal = Animal::where('code', $code)->first();
    //
    //    $statuses = Status::all();
    //
    //    foreach ($statuses as $key => $status) {
    //
    //        if($status->id == $animal->status){
    //
    //            $animal->status = $status->name;
    //
    //        }
    //
    //    }
    //
    //    return $animal;
    //
    //}
    //
    //
    //
    //public function getAllStatus(){
    //
    //    $statuses = Status::all();
    //
    //    return $statuses;
    //
    //}

}

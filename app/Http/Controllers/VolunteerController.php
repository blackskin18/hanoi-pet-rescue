<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\UserRole;
use App\RoleInfo;
use App\AnimalFoster;
use App\History;
use App\AnimalHospital;
use App\Hospital;
use App\AnimalImage;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ChangePhotoRequest;
use Redirect;
use File;
use Auth;

class VolunteerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getListVolunteer()
    {
    	return view('volunteer/list_volunteer');
    }

    public function postListVolunteer()
    {
        $userId = Auth::user()->id;
        $userRoles = UserRole::where('user_id', $userId)->get();
        $level = 100;
        foreach ($userRoles as $key => $userRole) {
            if($userRole->role_id < $level){
                $level = $userRole->role_id;
            }
        }

        $user = User::All();
        return ['volunteers' => $user, 'user_level' => $level];
    }

    public function volunteerInfo($user_id)
    {   
        $user = User::find($user_id);
        $levers = UserRole::where('user_id', Auth::user()->id)->get();
        $level = 100;
        foreach ($levers as $key => $value) {
            if($value->role_id < $level){
                $level = $value->role_id;
            }
        }

        $userId = Auth::user()->id;
        if($level == 3 || $level == 2){
            $roleInfos = RoleInfo::where('role_id', '>', 3)->get();
        } elseif($level == 1){
            $roleInfos = RoleInfo::all();
        }

        if(!isset($roleInfos)){
            $roleInfos = [];
        }

        $roleOfUsers = UserRole::where('user_id', $user_id)->get();
        foreach ($roleOfUsers as $key => $userRole) {
            $userRole->role;
        }

        $histories = $this->handleGetAllUserHistory($user_id);
        $animalFosters = $this->HandleGetAnimalFosterImage($user_id);
        return view('volunteer/detail_info')->with('user',$user)
                                            ->with('level', $level)
                                            ->with('role_infos', $roleInfos)
                                            ->with('user_roles', $roleOfUsers)
                                            ->with('histories', $histories)
                                            ->with('images', $animalFosters);
    }

    public function handleGetAllUserHistory($user_id) 
    {
        $histories = History::where('user_id', $user_id)->orderBy('created_at','desc')->get();
        
        foreach ($histories as $key => $value) {
            $histories[$key]->user;
            if($histories[$key]->attribute == 'place'){
                if($histories[$key]->old_value && $histories[$key]->old_value == 'hospital'){
                    $place = AnimalHospital::where('animal_id', $histories[$key]->animal_id)->orderBy('created_at', 'desc')->take(1)->get();
                    $place[0]->hospital;
                    $histories[$key]['old_value_place'] =  $place[0];
                } elseif($histories[$key]->old_value == 'volunteer'){
                    $place = AnimalFoster::where('animal_id', $histories[$key]->animal_id)->orderBy('created_at', 'desc')->take(1)->get();
                    $place[0]->foster;
                    $histories[$key]['old_value_place'] =  $place[0];
                }

                if($histories[$key]->new_value && $histories[$key]->new_value == 'hospital'){
                    $place = AnimalHospital::where('animal_id', $histories[$key]->animal_id)->orderBy('created_at', 'desc')->take(1)->get();
                    $place[0]->hospital;
                    $histories[$key]['new_value_place'] =  $place[0];
                } elseif($histories[$key]->new_value == 'volunteer'){
                    $place = AnimalFoster::where('animal_id', $histories[$key]->animal_id)->orderBy('created_at', 'desc')->take(1)->get();
                    $place[0]->foster;
                    $histories[$key]['new_value_place'] =  $place[0];
                }
            }
        }
        return $histories;
    }


    public function HandleGetAnimalFosterImage($user_id) 
    {
        $listRemove = [];
        $animalFosters = AnimalFoster::orderBy('animal_id')->where('foster_id', $user_id)->get();
        foreach ($animalFosters as $key => $animalFoster) {
            $animalImage = AnimalImage::where('animal_id', $animalFoster->animal_id)->get();
            if(!$animalImage->isEmpty()){
                $animalFosters[$key]['file_name'] = $animalImage[0]->file_name;          
            }
            if($key > 0 && $animalFosters[$key-1]->animal_id == $animalFoster->animal_id){
                $listRemove[] = $key;
            }
        }
        if($listRemove){
            foreach ($listRemove as $key => $value) {
                unset($animalFosters[$value]);
            }
        }
        return $animalFosters;   
    }


    public function editInfo(Request $request, $userId)
    {   
        $levers = UserRole::where('user_id', Auth::user()->id)->get();
        $level = 100;
        foreach ($levers as $key => $value) {
            if($value->role_id < $level){
                $level = $value->role_id;
            }
        }

        if($userId == Auth::User()->id OR $level <= 3) {
            $userInfo = $request->data;
            $user = User::find($userId);
            if($userInfo['note'] != null && $user->note !=  $userInfo['note']){
                $user->note = $userInfo['note'];
            }
            if($userInfo['name'] != null && $user->name !=  $userInfo['name']){
                $user->name = $userInfo['name'];
            }

            if($userInfo['phone'] != null && $user->phone !=  $userInfo['phone']){

                $user->phone = $userInfo['phone'];
            }
            if($userInfo['address'] != null && $user->address !=  $userInfo['address']){
                $user->address = $userInfo['address'];
            }
            if($userInfo['gender'] != null && $user->gender !=  $userInfo['gender']){
                $user->gender = $userInfo['gender'];
            }
            $user->save();
            $newUserRoles;
            if($userInfo['user_roles'] != null and $level <=3 ){
                $oldUserRoles = UserRole::where('user_id', $userId)->get();
                foreach ($oldUserRoles as $key => $value) {
                    $value->delete();
                }
                $newUserRoles = $userInfo['user_roles'];
                foreach ($newUserRoles as $key => $value) {
                    $userRole = new UserRole();
                    $userRole->user_id = $userId;
                    $userRole->role_id = $value;
                    $userRole->save();
                }
            }
        }

        return $user;

    }

    public function changeAvatar(ChangePhotoRequest $request, $userId)
    {   
        if($userId != Auth::User()->id){

        } else {

            $user = User::find(Auth::User()->id);

            if(!$user->avatar){

                $avatar = $request->photo;

                $fileName = $avatar->store('');

                $avatar->move('/home/okv08coo9fkv/public_html/hanoipetrescue'.'/avatar/'.$user->id, $fileName);

                $user->avatar = $fileName;

                $user->save();

            } else {

                $fileName = $user->avatar;

                File::Delete('/home/okv08coo9fkv/public_html/hanoipetrescue'.'/avatar/'.$user->id.'/'.$fileName);

                $avatar = $request->photo;

                $avatar->move('/home/okv08coo9fkv/public_html/hanoipetrescue'.'/avatar/'.$user->id, $fileName);

            }

        }

        return Redirect::to('/volunteer/info/'.$userId);

    }



    public function getAllVolunteer()

    {

        

    }



    public function deleteUser($userId){

        $user = User::find($userId);

        $user->delete();

        return 'true';

    }



    public function getListOwner(){

        return view('volunteer/list_owner');

    }



    public function postListOwner(){

        $userId = Auth::user()->id;

        $userRoles = UserRole::where('user_id', $userId)->get();

        $level = 100;

        foreach ($userRoles as $key => $userRole) {

            if($userRole->role_id < $level){

                $level = $userRole->role_id;

            }

        }



        // $user = User::All();

        $user =  DB::table('users')

                    ->leftJoin('user_roles', 'users.id', '=', 'user_roles.user_id')

                    ->leftJoin('role_infos', 'user_roles.role_id', '=', 'role_infos.id')

                    ->where('role_infos.id', 8)

                    ->get();



        return ['volunteers' => $user, 'user_level' => $level];

    }





}
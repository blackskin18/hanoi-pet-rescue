<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Animal;
use App\AnimalImage;
use Illuminate\Support\Facades\DB;
use Redirect;

class HomeController extends Controller
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

    public function getList($attribute, $value)
    {
        $animals = DB::table('animals')
                    ->leftJoin('animal_images', 'animals.id', '=', 'animal_images.animal_id')
                    ->leftJoin('statuses', 'animals.status', 'statuses.id', '=')
                    ->select('animals.*', 'animal_images.file_name', 'statuses.name as status'  )
                    ->where($attribute, $value)
                    ->orderBy('animals.code', 'desc')
                    ->get();

        foreach ($animals as $key => $animal) {
            $timeReceive = $animals[$key]->time_receive;
            $timeReceiveInt = strtotime( $timeReceive );
            $animals[$key]->time_receive = date( 'd-m-Y', $timeReceiveInt );

            if($animals[$key]->updated_at){
                $updateAt = $animals[$key]->updated_at;
                $updateAtInt = strtotime( $updateAt );
                $animals[$key]->updated_at = date( 'd-m-Y', $updateAtInt );
            }
            if($key != 0 && $animals[$key-1]->id == $animals[$key]->id ){
                $a[] = $key;
            }
        }
        if(isset($a)){
            foreach ($a as $key => $b) {
                unset($animals[$b]);
            }
        }
        return $animals;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
        // return Redirect::to('auth/google');
    }

    public function postListAllAnimal()
    {
        $animals = DB::table('animals')
                    ->leftJoin('animal_images', 'animals.id', '=', 'animal_images.animal_id')
                    // ->join('animal_status_histories', 'animals.id', '=', 'animal_status_histories.animal_id')
                    ->leftJoin('statuses', 'animals.status', 'statuses.id', '=')
                    ->select('animals.*', 'animal_images.file_name', 'statuses.name as status'  )
                    ->orderBy('animals.code', 'desc')
                    ->get();

        foreach ($animals as $key => $animal) {
            $timeReceive = $animals[$key]->time_receive;
            $timeReceiveInt = strtotime( $timeReceive );
            $animals[$key]->time_receive = date( 'd-m-Y', $timeReceiveInt );

            if($animals[$key]->updated_at){
                $updateAt = $animals[$key]->updated_at;
                $updateAtInt = strtotime( $updateAt );
                $animals[$key]->updated_at = date( 'd-m-Y', $updateAtInt );
            }

            if($key != 0 && $animals[$key-1]->id == $animals[$key]->id ){
                $a[] = $key;
            }
        }
        if(isset($a)){
            foreach ($a as $key => $b) {
                unset($animals[$b]);
            }
        }
        return $animals;
    }

    public function getListInCommonHome()
    {
        return view('animal/list_in_common_home');
    }

    public function postListInCommonHome()
    {
        $animals = $this->getList('animals.place','commonHome');
        return $animals;
    }

    public function getListReadyToFindTheOwner()
    {
        return view('animal/list_ready_to_find_the_owner');
    }

    public function postListReadyToFindTheOwner()
    {
        $animals = $this->getList('statuses.id',2);
        return $animals;
    }

    public function getListHasOwner()
    {
        return view('animal/list_has_owner');
    }
    public function postListHasOwner()
    {
        $animals = $this->getList('statuses.id',3);
        return $animals;
    }

    public function getListDie ()
    {
        return view('animal/list_die');
    }
    public function postListDie()
    {
        $animals = $this->getList('statuses.id',4);
        return $animals;
    }

    public function getListImageAnimal(){
        // $images = AnimalImage::orderBy('animal_id', 'desc')->take(70)->get();
        $images = DB::table('animal_images')
                    ->leftJoin('animals', 'animals.id', '=', 'animal_images.animal_id')
                    ->orderBy('animal_id', 'desc')
                    ->take(70)
                    ->get();
        foreach ($images as $key => $image) {
            if($key != 0 && $images[$key-1]->animal_id == $images[$key]->animal_id ){
                $a[] = $key;
            }
        }
        if(isset($a)){
            foreach ($a as $key => $b) {
                unset($images[$b]);
            }
        }

        $sumImage = AnimalImage::orderBy('animal_id', 'desc')->take(1)->get();
        return view('animal/list_image_all_animal')->with('images', $images )->with('sum_image', $sumImage[0]->id);
    }

    public function getMoreToLisstAllImage($animalId)
    {
        // $images = AnimalImage::orderBy('animal_id', 'desc')->where('animal_id', '<', $animalId )->take(70)->get();
        $images = DB::table('animal_images')
                    ->leftJoin('animals', 'animals.id', '=', 'animal_images.animal_id')
                    ->where('animal_id', '<', $animalId)
                    ->orderBy('animal_id', 'desc')
                    ->take(70)
                    ->get();
        foreach ($images as $key => $image) {
            if($key != 0 && $images[$key-1]->animal_id == $images[$key]->animal_id ){
                $a[] = $key;
            }
        }
        if(isset($a)){
            foreach ($a as $key => $b) {
                unset($images[$b]);
            }
        }

        return $images;
    }

}
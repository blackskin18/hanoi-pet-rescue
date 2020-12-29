<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Models\Animal;

class AnimalController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function checkCodeMissing()
    {
        $lastCode = Animal::max('code');
        $codeMissing = [];
        $codeMissingPlace = [];
        $typeOther = [];
        for($i = 1; $i <= $lastCode;$i++) {
            $animal = Animal::where('code', $i)->first();
            if(!$animal) {
                $codeMissing[] = $i;
                continue;
            }
            if($animal->place == null) {
                $codeMissingPlace[] = $i;
            }
            if($animal->type == 3) {
                $typeOther[] = $i;
            }
        }

        return view('missing_info', [
            'code_missing' =>  $codeMissing,
            'missing_place' => $codeMissingPlace,
            'type_other' => $typeOther,
        ]);
    }

}

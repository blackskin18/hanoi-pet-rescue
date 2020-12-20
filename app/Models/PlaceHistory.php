<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlaceHistory extends Model
{
    const HOSPITAL = 1;
    const COMMON_HOME = 2;
    const FOSTER = 3;
    const OWNER = 4;

    public $table = "place_histories";
    protected $fillable = [
        'id',
        'place_id',
        'animal_id',
    ];

    public function place() {
        return $this->belongsTo('App\Models\Place');
    }

    public function animal() {
        return $this->belongsTo('App\Models\Animal');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    const HOSPITAL = 1;
    const COMMON_HOME = 2;
    const FOSTER = 3;
    const OWNER = 4;

    public $table = "places";
    protected $fillable = [
        'id',
        'name',
        'phone',
        'address',
        'note',
        'director_name',
        'director_phone',
        'type',
        'parent_id',
    ];

    public function parent() {
        return $this->belongsTo('App\Models\Place', 'parent_id', 'id');
    }
}

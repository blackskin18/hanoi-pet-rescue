<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\AnimalImage;

class Animal extends Model
{
    //public static $limitDefault = 20;
    const LIMIT_DEFAULT = 20;

    public $table = "animals";

    protected $fillable = [
        'id',
        'code',
        'name',
        'input_date',
        'age',
        'description',
        'address',
        'status',
        'note',
        'type',
        'change_status_date',
        'statuses.name',
        'date_of_birth',
        'created_by',
    ];

    public function animalImage()
    {
        return $this->hasMany('App\Models\AnimalImage');
    }

    public function status()
    {
        return $this->hasOne('App\Models\Status', 'id', 'status');
    }
}

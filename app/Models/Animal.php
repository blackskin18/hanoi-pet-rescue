<?php

namespace App\Models;

use App\Events\AnimalDeleted;
use Illuminate\Database\Eloquent\Model;
use App\AnimalImage;

class Animal extends Model
{
    //public static $limitDefault = 20;
    const LIMIT_DEFAULT = 20;

    const GENDER_M = 1;

    const GENDER_F = 2;

    const GENDER_O = 3;

    const TYPE_DOG = 1;

    const TYPE_CAT = 2;

    const TYPE_OTHER = 3;

    public $table = "animals";

    protected $fillable = [
        'id',
        'code',
        'code_full',
        'name',
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
        'place_id',
        'foster_id',
        'receive_date',
        'receive_place',
        'gender',
        'owner_id',
    ];

    public static function boot() {
        parent::boot();

        static::deleting(function($animal) { // before delete() method call this
            $animal->animalImage()->delete();
        });
    }

    public function animalImage()
    {
        return $this->hasMany('App\Models\AnimalImage');
    }

    public function status()
    {
        return $this->hasOne('App\Models\Status', 'id', 'status');
    }

    public function history()
    {
        return $this->hasMany('App\Models\History');
    }

    public function place()
    {
        return $this->belongsTo('App\Models\Place');
    }

    public function foster()
    {
        return $this->belongsTo('App\Models\Place');
    }
    public function owner()
    {
        return $this->belongsTo('App\Models\Place');
    }
}

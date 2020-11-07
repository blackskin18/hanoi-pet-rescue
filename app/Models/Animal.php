<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\AnimalImage;

class Animal extends Model
{
    public $table="animals";
    protected $fillable=['id','name','input_date','age','description','address','status','note','type','change_status_date', 'statuses.name', 'date_of_birth', 'created_by'];
    
    public function animalImage()
    {
    	return $this->hasMany('App\AnimalImage');
    }

    public function status()
    {
    	return $this->hasOne('App\Status');
    }
}

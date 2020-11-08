<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    public $table="statuses";
    protected $fillable=['id','animal_id','name'];

    public function animal()
    {
        return $this->belongsTo('App\Animal');
    }
    
}

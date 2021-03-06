<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\RoleUser;

class User extends Authenticatable implements JWTSubject
{
    CONST MEDICAL = 1;

    CONST VOLUNTEER = 2;

    CONST FOSTER = 3;

    CONST MEDICAL_IDS = [1, 2, 11, 10];

    CONST VOLUNTEER_IDS = [9];

    CONST FOSTER_IDS = [6];

    use Notifiable;

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'provider',
        'provider_id',
        'address',
        'note',
        'phone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public static function boot() {
        parent::boot();

        static::deleting(function($user) { // before delete() method call this
            RoleUser::where('user_id', $user->id)->delete();
        });
    }

    public function animals() {
        return $this->hasMany('App\Models\Animal','foster_id', 'id');
    }


    public function animalCreated() {
        return $this->hasMany('App\Models\Animal','created_by', 'id');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Models\Role');
    }
}

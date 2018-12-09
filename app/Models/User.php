<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\StationUsers;
use App\Models\UserNotifications;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fullname','username', 'email', 'password', 'phone_number', 'is_verified',
        'auth_key', 'is_term_agreed', 'is_company_set_up','role_id','company_id', 'created_at', 'updated_at', 'v1_id', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','auth_key',
    ];
    public function isAdminstrator(){
        return $this->roles();
    }

    public function getJWTIdentifier(){
        return $this->getKey();
    }

    public function getJWTCustomClaims(){
        return [];
    }

     public function role() {
        return $this->hasOne(Role::class,'id', 'role_id');
   }
    public function companies() {
        return $this->belongsTo(Company::class,'company_id');
    }

    public function user_notifications() {
        return $this->hasMany(UserNotifications::class,'company_user_id');
    }
   
    public function station_users(){
        return $this->hasMany(StationUsers::class,"company_user_id");
    }

}

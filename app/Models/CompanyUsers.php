<?php

namespace App\Models;

use App\Models\CompanyUserRole;
use App\Company;
use App\Role;
use App\Station;
use App\Models\StationUsers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
//use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;

class CompanyUsers extends Authenticatable 
{
    use Notifiable;
	
    protected $fillable = [
        'fullname','username', 'email', 'password', 'phone_number','company_id',
        'type','is_password_reset','role_id'
    ];
    public function role() {
        return $this->hasOne(Role::class,'id', 'role_id');
   }
    public function companies() {
        return $this->belongsTo(Company::class,'company_id');
    }
   
    public function station_users(){
        return $this->hasMany(StationUsers::class,"company_user_id");
    }
    public function getJWTIdentifier(){
        return $this->getKey();
    }

    public function getJWTCustomClaims(){
        return [];
    }
   
}

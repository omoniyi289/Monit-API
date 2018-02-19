<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
        'auth_key', 'gender', 'is_term_agreed', 'is_company_set_up',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','auth_key',
    ];

    public function roles() {
        return $this->belongsToMany(Role::class,"user_roles","user_id","role_id");
    }

    public function companies(){
        return $this->hasOne(User::class);
    }

    public function isAdminstrator(){
        return $this->roles();
    }

    public function getJWTIdentifier(){
        return $this->getKey();
    }

    public function getJWTCustomClaims(){
        return [];
    }



}

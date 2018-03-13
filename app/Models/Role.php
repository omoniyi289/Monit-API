<?php

namespace App;

use App\RolePermission;
use Illuminate\Database\Eloquent\Model;
class Role extends Model
{
    protected $fillable = ['name','description','company_id','active'];

    public function users() {
        return $this->belongsToMany(User::class,"user_roles","user_id","role_id");
    }

    public function permissions(){
    	return $this->hasMany(RolePermission::class, "role_id");
    }



}

<?php

namespace App;

use App\RolePermission;
use App\Permission;
use App\User;
use Illuminate\Database\Eloquent\Model;
class Role extends Model
{
    protected $fillable = ['id','name','description','company_id','active', 'v1_id'];

    public function users() {
        return $this->hasMany(User::class);
    }

    public function role_permissions(){
    	return $this->hasMany(RolePermission::class,"role_id");
    }
    //public function permissions(){
    	//return $this->hasMany(RolePermission::class,"role_id");
   // 	return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id');
   // }



}

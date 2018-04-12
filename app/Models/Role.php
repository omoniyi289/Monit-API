<?php

namespace App;

use App\RolePermission;
use App\Permission;
use App\Models\CompanyUsers;
use Illuminate\Database\Eloquent\Model;
class Role extends Model
{
    protected $fillable = ['name','description','company_id','active'];

    public function users() {
        return $this->belongsToMany(User::class,"user_roles","user_id","role_id");
    }
    public function users_via_permission() {
        return $this->hasMany(CompanyUsers::class, "role_id", "id");
    }

    public function role_permissions(){
    	return $this->hasMany(RolePermission::class,"role_id");
    }
    //public function permissions(){
    	//return $this->hasMany(RolePermission::class,"role_id");
   // 	return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id');
   // }



}

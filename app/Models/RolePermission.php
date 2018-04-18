<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use App\Permission;
use App\Role;

class RolePermission extends Model
{
    protected $fillable = [
        'permission_id','role_id', 'permission_name', 'company_id'
    ];

   
 protected $table = 'role_permissions';

   public function permissions(){
    	return $this->hasOne(Permission::class, "permission_id");
    } 
    public function permission(){
        return $this->belongsTo(Permission::class,"permission_id");
    }  
    public function roles(){
        return $this->hasMany(Role::class, 'id', "role_id");
    }  
     
}
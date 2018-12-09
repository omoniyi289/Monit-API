<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyUserRole extends Model
{
    protected $fillable = [
        'company_user_id','role_id'
    ];

   
 protected $table = 'company_user_roles';
 //public function company_user_role_privileges() {
   //     return $this->hasManyThrough(Permission::class, RolePermission::class, 'permission_id', 'id', );
    //}

    
}
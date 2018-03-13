<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $fillable = [
        'permission_id','role_id'
    ];

   
 protected $table = 'role_permissions';

    
}
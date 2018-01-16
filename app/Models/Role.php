<?php

namespace App;

use Core\Models\Model;

class Role extends Model
{
    protected $fillable = ['name','role_type','active'];

    public function users() {
        return $this->belongsToMany(User::class,"user_roles","user_id","role_id");
    }

    public function permissions(){

    }

}

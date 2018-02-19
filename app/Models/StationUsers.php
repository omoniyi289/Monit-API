<?php

namespace App;

use Core\Models\Model;

class StationUsers extends Model
{
    protected $fillable = [
        'fullname','username', 'email', 'password', 'phone_number','company_id',
        'type','is_password_reset',
    ];

    public function companies() {
        return $this->belongsTo(Company::class,'company_id');
    }


}

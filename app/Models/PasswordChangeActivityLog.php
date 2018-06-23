<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Station;

class PasswordChangeActivityLog extends Model
{
    //
     protected $table = 'password_change_activity_log';
    protected $fillable = [	'change_time', 	'email', 'user_id', 'location_address' ];
    

}

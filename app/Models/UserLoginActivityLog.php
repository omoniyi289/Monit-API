<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Station;

class UserLoginActivityLog extends Model
{
    //
     protected $table = 'user_login_activity_log';
    protected $fillable = [	'login_time', 	'email', 'user_id' ,'browser_name' ,'browser_version', 'os_version' ];
    

}

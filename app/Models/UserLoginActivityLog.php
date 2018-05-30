<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Station;

class UserLoginActivityLog extends Model
{
    //
    protected $fillable = [	'login_time', 	'email', 	'user_id'];
    

}

<?php

namespace App\Models;

//use Core\Models\Model;
use Illuminate\Database\Eloquent\Model;

class StationUsers extends Model
{
    //
    protected $table = 'stations_users'; 
    protected $fillable = [
        'company_user_id',
        'station_id' 
    ];

   
}

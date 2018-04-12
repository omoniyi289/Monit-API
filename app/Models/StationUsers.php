<?php

namespace App\Models;

use App\Station;
use Illuminate\Database\Eloquent\Model;

class StationUsers extends Model
{
    //
    protected $table = 'stations_users'; 
    protected $fillable = [
        'company_user_id',
        'station_id' 
    ];

 	public function station(){
        return $this->belongsTo(Station::class,"station_id");
    }  
}

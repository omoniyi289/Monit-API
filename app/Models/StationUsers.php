<?php

namespace App\Models;

use App\Station;
use Illuminate\Database\Eloquent\Model;
use App\User;
class StationUsers extends Model
{
    //
    protected $table = 'stations_users'; 
    protected $fillable = [
        'company_user_id',
        'station_id' ,'v1_id', 'created_at', 'has_access'
    ];

 	public function station(){
        return $this->belongsTo(Station::class,"station_id");
    }  
    public function user(){
        return $this->belongsTo(User::class,"company_user_id", "id");
    }  
}

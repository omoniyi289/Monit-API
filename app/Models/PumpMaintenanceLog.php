<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Station;
use App\User;

class PumpMaintenanceLog extends Model
{
    //
    protected $table = 'pump_maintenance_log';

    protected $fillable = ['company_id', 'product_id','approved_by','station_id', 'pump_id',"nozzle_code","totalizer_before_maintenance","totalizer_after_maintenance","maintenance_date","note","created_by"];

    public function station(){
        return $this->belongsTo(Station::class, 'station_id');
    }
    public function created_by(){
        return $this->belongsTo(User::class, 'created_bys');
    }
   

}

<?php

namespace App\Model;

//use Core\Models\Model;
use Illuminate\Database\Eloquent\Model;

class ROPS extends Model
{
    //
    protected $fillable = [
        'pump_nozzle_code' , 'brand' , 'serial_number' , 'fcc_pump_nozzle_id' , 'company_id',
        'station_id' , 'pump_group_id' , 'product_id', 'created_at', 'updated_at','v1_id' ];
}

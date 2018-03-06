<?php

namespace App;

//use Core\Models\Model;
use Illuminate\Database\Eloquent\Model;

class Pumps extends Model
{
    //
    protected $fillable = [
        'number' , 'brand' , 'serial_number' , 'type' , 'company_id',
        'station_id' , 'pump_group_id' , 'product_id','nozzle_code'
    ];

    public function pump_group() {
        return $this->belongsTo(PumpGroups::class);
    }
    public function product(){
        return $this->belongsTo(Products::class, 'product_id');
    }
}

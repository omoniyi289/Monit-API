<?php

namespace App;

use Core\Models\Model;

class Pumps extends Model
{
    //
    protected $fillable = [
        'number' , 'brand' , 'serial_number' , 'type' , 'company_id',
        'station_id' , 'pump_group_id' , 'product_id',
    ];

    public function pump_groups() {
        return $this->belongsTo(PumpGroups::class);
    }
}

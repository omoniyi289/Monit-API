<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PumpGroupTOTankGroup extends Model
{
    protected $fillable = [
        'name','company_id','station_id','tank_group_id', 'pump_group_id'
    ];

   
 protected $table = 'pump_group_to_tank_group';

     public function get_tank_group()
    {
        return $this->belongsTo(TankGroups::class, 'tank_group_id');
    }

     public function get_pump_group()
    {
        return $this->belongsTo(PumpGroups::class, 'pump_group_id');
    }
}
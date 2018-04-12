<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PumpGroups extends Model
{
    protected $fillable = [
        'name','code','station_id','company_id',
    ];

    public function pumps(){
        return $this->hasMany(Pumps::class , 'pump_group_id');
    }
}



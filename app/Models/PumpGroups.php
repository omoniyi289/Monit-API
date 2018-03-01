<?php

namespace App;

use Core\Models\Model;

class PumpGroups extends Model
{
    protected $fillable = [
        'name','code','station_id','company_id',
    ];

    public function pumps(){
        return $this->hasMany(Pumps::class);
    }
}



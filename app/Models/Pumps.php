<?php

namespace App;

use Core\Models\Model;

class Pumps extends Model
{
    //
    protected $fillable = [

    ];

    public function pump_groups(){
        return $this->belongsTo(PumpGroups::class);
    }
}

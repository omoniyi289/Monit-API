<?php

namespace App;

use Core\Models\Model;

class Tanks extends Model
{
    //
    protected $fillable = [
        'name','code', 'company_id', 'station_id', 'width' , 'height' , 'shape',
        'capacity' , 'product_id', 'low_volume' , 'reorder_volume','deadstock', 'max_temperate',
        'max_water_level','daily_budget','tank_group_id'
    ];

    public function tank_groups() {
        return $this->belongsTo(TankGroups::class);
    }

    public function stations() {
        return $this->belongsTo(Station::class);
    }

    public function compaines(){
        return $this->belongsTo(Company::class);
    }

    public function products(){
        return $this->belongsTo(Products::class);
    }
}

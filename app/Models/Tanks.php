<?php

namespace App;

//use Core\Models\Model;
use Illuminate\Database\Eloquent\Model;


class Tanks extends Model
{
    //
    protected $fillable = [
        'name','code', 'company_id', 'station_id', 'width' , 'height' , 'shape',
        'capacity' , 'product_id', 'low_volume' , 'reorder_volume','deadstock', 'max_temperate',
        'max_water_level','daily_budget','tank_group_id'
    ];

    public function tank_group() {
        return $this->belongsTo(TankGroups::class);
    }

    public function stations() {
        return $this->belongsTo(Station::class);
    }

    public function companies(){
        return $this->belongsTo(Company::class);
    }

    public function product(){
        return $this->belongsTo(Products::class, 'product_id');
    }
}

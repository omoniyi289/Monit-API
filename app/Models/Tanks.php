<?php

namespace App;

//use Core\Models\Model;
use Illuminate\Database\Eloquent\Model;


class Tanks extends Model
{
    //
    protected $fillable = [
        'code', 'company_id', 'station_id', 'width' , 'height' , 'probe_id',
        'capacity' , 'product_id' , 'reorder_volume','deadstock', 'atg_tank_id',
        'max_water_level','tank_group_id', 'type',
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

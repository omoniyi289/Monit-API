<?php

namespace App;

use Core\Models\Model;

class Company extends Model
{
    protected $fillable = [
        'name', 'email', 'registration_number', 'country', 'state', 'city',
        'address',
    ];

    public function users() {
        return $this->hasMany(StationUsers::class);
    }

    public function stations() {
        return $this->hasMany(Station::class);
    }

    public function tank_groups() {
        return $this->hasMany(TankGroups::class);
    }


}

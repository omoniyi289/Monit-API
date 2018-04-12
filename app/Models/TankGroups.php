<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TankGroups extends Model
{
    //
    protected $fillable = [
      'code','name','company_id','station_id',
    ];

    public function companies(){
        return $this->belongsTo(Company::class);
    }

    public function tanks(){
        return $this->hasMany(Tanks::class, 'tank_group_id');
    }

}

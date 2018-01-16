<?php

namespace App;

use Core\Models\Model;

class TankGroups extends Model
{
    //
    protected $fillable = [
      'code','name','company_id','station_id',
    ];

    public function compaines(){
        return $this->belongsTo(Company::class);
    }

    public function tanks(){
        return $this->hasMany(Tanks::class);
    }

}

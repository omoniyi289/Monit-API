<?php

namespace App;

use Core\Models\Model;

class Activations extends Model
{
    protected $fillable = [
        'activation_code', 'license_type', 'activation_date', 'expiry_date' , 'station_id',
    ];

    protected $hidden = [
      'activation_code'
    ];

    public function stations(){
        return $this->hasOne(Station::class);
    }
}

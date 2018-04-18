<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Station;

class Items extends Model
{
    //
    protected $fillable = ['description','created_by', 'company_id', 'parentsku','hasvariants','station_id', 'name', 'category', 'status', 'brand', 'uom', 'modified_by', 'active'
    ];

    public function station(){
        return $this->belongsTo(Station::class, 'station_id');
    }
    

}

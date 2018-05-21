<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\StationRegions;
use App\Company;
class Region extends Model
{
    protected $fillable = [
        'name','address',
        'company_id', 'manager_email' , 'manager_name' , 'manager_phone' , 'created_at'
    ];

    public function company() {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function region_stations(){
        return $this->hasMany(StationRegions::class,"region_id");
    }
}

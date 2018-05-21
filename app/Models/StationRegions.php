<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\StationUsers;
use App\Station;
use App\Company;

class StationRegions extends Model
{
    protected $fillable = [ 'station_id', 'region_id',
        'company_id',  'active'
    ];
    protected $table = 'region_stations';

    public function company() {
        return $this->belongsTo(Company::class, 'company_id');
    }

public function region() {
        return $this->belongsTo(Region::class, 'region_id');
    }
        public function station() {
        return $this->belongsTo(Station::class, 'station_id');
    }
}

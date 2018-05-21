<?php

namespace App\Models;

use App\Tanks;
use Illuminate\Database\Eloquent\Model;
use App\Station;
use App\Company;
class DailyOperationsReportLog extends Model
{
	 protected $table = 'daily_operations_report';
	 protected $fillable = ['station_name', 'upload_date'];

    public function station() {
        return $this->belongsTo(Station::class,'name', 'station_name');
    }
}

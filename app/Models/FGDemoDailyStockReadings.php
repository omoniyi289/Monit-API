<?php

namespace App\Models;

use App\Tanks;
use Illuminate\Database\Eloquent\Model;
use App\Models\FGDemoStation;
use App\Models\FGDemoCompany;
class FGDemoDailyStockReadings extends Model
{
	 protected $table = 'FGdemo_daily_stock_readings';
	 protected $fillable = ['company_id', 'station_id','tank_id','tank_code','phy_shift_start_volume_reading',"phy_shift_end_volume_reading", "atg_shift_start_volume_reading","atg_shift_end_volume_reading","start_delivery","end_delivery","return_to_tank","created_by","last_modified_by","status", 'created_at','v1_id', 'product', 'reading_date'];

    public function tank() {
        return $this->belongsTo(Tanks::class,'tank_id');
    }
    public function station() {
        return $this->belongsTo(FGDemoStation::class,'station_id');
    }
    public function company() {
        return $this->belongsTo(FGDemoCompany::class,'company_id');
    }
}

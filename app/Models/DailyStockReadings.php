<?php

namespace App\Models;

//use Core\Models\Model;
use Illuminate\Database\Eloquent\Model;

class DailyStockReadings extends Model
{
	 protected $table = 'daily_stock_readings';
	 protected $fillable = ['company_id', 'station_id','tank_id','tank_code','phy_shift_start_volume_reading',"phy_shift_end_volume_reading", "atg_shift_start_volume_reading","atg_shift_end_volume_reading","start_delivery","end_delivery","return_to_tank","created_by","last_modified_by","status"];
}

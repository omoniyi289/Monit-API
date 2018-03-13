<?php

namespace App\Models;

//use Core\Models\Model;
use Illuminate\Database\Eloquent\Model;

class DailyTotalizerReadings extends Model
{
	 protected $table = 'daily_totalizer_readings';
	 protected $fillable = ['company_id','station_id','pump_id',"pump_number","nozzle_code","open_shift_totalizer_reading","close_shift_totalizer_reading","shift_1_totalizer_reading","shift_2_totalizer__reading","shift_1_cash_collected","shift_2_cash_collected","cash_collected","attendant","ppv","created_by","last_modified_by","status"];
}

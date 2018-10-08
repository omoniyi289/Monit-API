<?php

namespace App\Models;

use App\Pumps;
use Illuminate\Database\Eloquent\Model;
use App\Station;
use App\Company;
class DailyTotalizerReadings extends Model
{
	 protected $table = 'daily_totalizer_readings';
	 protected $fillable = ['company_id','station_id','created_at', 'pump_id',"pump_number","nozzle_code","open_shift_totalizer_reading","close_shift_totalizer_reading","shift_1_totalizer_reading","shift_2_totalizer_reading","shift_1_cash_collected","shift_2_cash_collected",
	 "cash_collected","attendant","ppv","created_by","last_modified_by","status",'v1_id', 'product', 'reading_date', 'upload_type'];

	  public function pump() {
        return $this->belongsTo(Pumps::class,'pump_id');
    }
      public function station() {
        return $this->belongsTo(Station::class,'station_id');
    }
       public function company() {
        return $this->belongsTo(Company::class,'company_id');
    }
}

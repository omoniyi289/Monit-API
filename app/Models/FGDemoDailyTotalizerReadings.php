<?php

namespace App\Models;

use App\Pumps;
use Illuminate\Database\Eloquent\Model;
use App\Models\FGDemoStation;
use App\Models\FGDemoCompany;
class FGDemoDailyTotalizerReadings extends Model
{
     protected $table = 'FGdemo_daily_totalizer_readings';
     protected $fillable = ['company_id','station_id','created_at', 'pump_id',"pump_number","nozzle_code","open_shift_totalizer_reading","close_shift_totalizer_reading","shift_1_totalizer_reading","shift_2_totalizer_reading","shift_1_cash_collected","shift_2_cash_collected",
     "cash_collected","attendant","ppv","created_by","last_modified_by","status",'v1_id', 'product', 'reading_date', 'id'];

      public function pump() {
        return $this->belongsTo(Pumps::class,'pump_id');
    }
      public function station() {
        return $this->belongsTo(FGDemoStation::class,'station_id');
    }
       public function company() {
        return $this->belongsTo(FGDemoCompany::class,'company_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Station;
use App\User;

class PumpMaintenanceLog extends Model
{
    //
    protected $table = 'new_pump_maintenance_log';

    protected $fillable = ['company_id', 'product_id','station_id','combined_pump_nozzle_code',"pump_1_nozzle_code","pump_2_nozzle_code","totalizer_1_reading","totalizer_2_reading","combined_totalizer_reading","D_invoice_number","D_issue_date","D_maintenance_date","MD_invoice_number","MD_issue_date","MD_maintenance_date","MMD_invoice_number","MMD_issue_date","MMD_maintenance_date","note","created_by", "product" ,'D_payment_status','MD_payment_status','MMD_payment_status'];

    public function station(){
        return $this->belongsTo(Station::class, 'station_id');
    }
    public function created_by(){
        return $this->belongsTo(User::class, 'created_bys');
    }
  

}

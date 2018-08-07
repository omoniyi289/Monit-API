<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VeloxFuelPurchaseHistory extends Model
{
     protected $connection = 'mysql2';
     protected $table = 'fuel_purchase_history';
     protected $fillable = ['vendor_id','company_id','company_branch_id','vendor_station_name','vehicle_plate_number','auth_type','barcode_id','nfctag_id','driver_id','volume','start_branch_balance','end_branch_balance','product','selling_price','current_credit_limit','attendant', 'amount_paid'];

    
}

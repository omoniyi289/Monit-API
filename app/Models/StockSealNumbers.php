<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\StockReceived;
class StockSealNumbers extends Model
{
	 protected $table = 'stock_seal_numbers';
	 protected $fillable = ['stock_received_id', 'request_code','compartment_number','previous_seal_number',"latest_seal_number",'previous_seal_quantity',"latest_seal_quantity", "compartment_number"];

    
    public function stock_received() {
        return $this->belongsTo(StockReceived::class,'stock_received_id');
    }
}

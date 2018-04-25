<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Station;

class StockTransfer extends Model
{
    //
    protected $fillable = [	'item_id', 	'company_id', 	'tx_station_id', 	'rx_station_id', 	'quantity_requested', 'quantity_transferred', 'quantity_received','requested_by', 	'transfered_by' ,'approved_by', 'received_by', 	'date_requested','date_transfered', 'date_received', 'date_approved', 	'status', 	'active', 	'in_stock', 'compositesku','v1_id'
    ];
    protected $table='stock_transfers';
    public function station(){
        return $this->belongsTo(Station::class, 'station_id');
    }
    public function item(){
        return $this->belongsTo(Items::class, 'item_id');
    }
    public function item_variant(){
        return $this->belongsTo(ItemVariants::class, 'compositesku','compositesku');
    }

}

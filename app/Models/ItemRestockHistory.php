<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Station;

class ItemRestockHistory extends Model
{
    //
    protected $fillable = ['variant_option','created_by', 'company_id', 'variant_value','restock_id','station_id', 'qty_in_stock', 'supply_price', 'status', 'last_restock_date', 'retail_price', 'modified_by', 'active', 'reorder_level','item_id', 'compositesku','restock_qty','qty_before_restock', 'qty_after_restock'
    ];
    protected $table='item_restock_history';
    public function station(){
        return $this->belongsTo(Station::class, 'station_id');
    }
    

}

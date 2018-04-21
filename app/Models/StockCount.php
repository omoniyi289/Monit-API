<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Station;

class StockCount extends Model
{
    //
    protected $fillable = ['created_by', 'company_id', 'in_stock','station_id', 'qty_in_stock', 'qty_counted', 'status', 'last_restock_date', 'retail_price', 'modified_by', 'active', 'reorder_level','item_id', 'compositesku'
    ];
    protected $table='stock_counts';
    public function station(){
        return $this->belongsTo(Station::class, 'station_id');
    }
    

}

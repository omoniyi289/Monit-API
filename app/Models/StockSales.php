<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Station;

class StockSales extends Model
{
    //
    protected $table = 'stock_sales_history';

    protected $fillable = ['id','company_id','station_id','item_id','compositesku','qty_sold','supply_price','qty_in_stock','retail_price','cash_collected','sold_by', 'note' ];

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

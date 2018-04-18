<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Station;

class ItemVariants extends Model
{
    //
    protected $fillable = ['variant_option','created_by', 'company_id', 'variant_value','hasvariants','station_id', 'qty_in_stock', 'supply_price', 'status', 'last_restock_date', 'retail_price', 'modified_by', 'active', 'sku'
    ];
    protected $table='itemvariants';
    public function station(){
        return $this->belongsTo(Station::class, 'station_id');
    }
    

}

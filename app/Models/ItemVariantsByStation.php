<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Station;
use App\Models\Items;

class ItemVariantsByStation extends Model
{
    //
    protected $fillable = ['variant_option','created_by', 'company_id', 'variant_value','hasvariants','station_id', 'qty_in_stock', 'supply_price', 'status', 'last_restock_date', 'retail_price', 'modified_by', 'active', 'reorder_level','item_id', 'compositesku',
    ];
    protected $table='itemvariants_by_station';
    public function station(){
        return $this->belongsTo(Station::class, 'station_id');
    }
    public function item(){
        return $this->belongsTo(Items::class, 'item_id');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Products;
use App\Station;
use App\Models\FuelSupply;
use App\Models\StockSealNumbers;
class StockReceived extends Model
{
    //
    protected $table = 'stock_received';
    protected $fillable = ['quantity_supplied','quantity_requested','quantity_loaded','stock_received_by', 'company_id', 'product_id','arrival_time','truck_departure_time','station_id', 'quantity_before_discharge', 'last_modified_by', 'truck_reg_number', 'request_code','quantity_after_discharge','waybill_number','driver_name','waybill_printed_by', 'waybill_path'
    ];

    public function station(){
        return $this->belongsTo(Station::class, 'station_id');
    }
    public function product(){
        return $this->belongsTo(Products::class ,'product_id');
    }
    public function fuelsupply(){
        return $this->belongsTo(FuelSupply::class ,'request_code', 'request_code');
    }
    public function stock_seal_numbers() {
        return $this->hasMany(StockSealNumbers::class,'request_code', 'request_code');
    }
}

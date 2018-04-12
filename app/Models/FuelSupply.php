<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Products;
use App\Models\CompanyUsers;
use App\Models\StockSealNumbers;

class FuelSupply extends Model
{
    //
    protected $fillable = ['quantity_requested','created_by', 'company_id', 'product_id','approved_by','station_id', 'is_approved', 'last_modified_by', 'status', 'request_code'
    ];

    public function station(){
        return $this->belongsTo(Station::class, 'station_id');
    }
    public function approver(){
        return $this->belongsTo(CompanyUsers::class, 'approved_by');
    }
    public function product(){
        return $this->belongsTo(Products::class ,'product_id');
    }
    public function stock_received(){
        return $this->belongsTo(StockReceived::class ,'request_code', 'request_code');
    }
    public function stock_seal_numbers() {
        return $this->hasMany(StockSealNumbers::class,'request_code', 'request_code');
    }

}

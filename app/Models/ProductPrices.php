<?php

namespace App;

//use Core\Models\Model;
use Illuminate\Database\Eloquent\Model;
class ProductPrices extends Model
{
    //
    protected $fillable = [
        'new_price_tag', 'product_id' , 'company_id', 'station_id'
    ];

    public function company() {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function station() {
        return $this->belongsTo(Station::class, 'station_id');
    }

    public function product() {
        return $this->belongsTo(Products::class, 'product_id');
    }
}

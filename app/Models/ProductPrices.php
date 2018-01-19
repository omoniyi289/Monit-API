<?php

namespace App;

use Core\Models\Model;

class ProductPrices extends Model
{
    //
    protected $fillable = [
        'new_price_tag', 'products_id' , 'company_id', 'station_id'
    ];

    public function compaines() {
        return $this->belongsTo(Company::class);
    }

    public function stations() {
        return $this->belongsTo(Station::class);
    }

    public function products() {
        return $this->belongsTo(Products::class);
    }
}

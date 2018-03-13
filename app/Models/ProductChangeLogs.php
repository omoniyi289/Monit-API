<?php

namespace App;

use Core\Models\Model;

class ProductChangeLogs extends Model
{
    //
    protected $fillable = [
        'current_price_tag','requested_price_tag', 'company_id', 'product_id' , 'updated_by',
        'approved_by','station_id', 'is_approved'
    ];

    public function station(){
        return $this->belongsTo(Station::class, 'id');
    }
    public function product(){
        return $this->hasOne(Products::class ,'id');
    }
}

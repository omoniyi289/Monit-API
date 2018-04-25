<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class ProductChangeLogs extends Model
{
    //
    protected $table = 'product_change_logs';
    protected $fillable = [
        'current_price_tag','requested_price_tag', 'company_id', 'product_id' , 'updated_by',
        'approved_by','station_id', 'is_approved','v1_id'
    ];

    public function station(){
        return $this->belongsTo(Station::class, 'id');
    }
    public function product(){
        return $this->belongsTo(Products::class ,'product_id');
    }
    public function approver(){
        return $this->hasOne(User::class ,'id', 'approved_by');
    }
}

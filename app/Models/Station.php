<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Station extends Model
{
    protected $fillable = [
        'name','address', 'opening_time','city','state','daily_budget','expenses_type',
        'company_id','station_user_id','is_station_enabled', 'monthly_budget' , 'license_type'
        , 'manager_email' , 'manager_name' , 'manager_phone' ,
    ];

    public function companies() {
        return $this->belongsTo(Company::class);
    }

    public function activations(){
        return $this->hasOne(Activations::class);
    }

    public function tanks_groups(){
        return $this->hasMany(TankGroups::class);
    }

    public function tanks(){
        return $this->hasMany(Tanks::class);
    }

    public function product_prices(){
        return $this->hasMany(ProductPrices::class);
    }
}

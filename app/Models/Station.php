<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\StationUsers;
class Station extends Model
{
    protected $fillable = [
        'name','address', 'opening_time','city','state','daily_budget','expenses_type',
        'company_id','station_user_id','is_station_enabled', 'monthly_budget' , 'license_type'
        , 'manager_email' , 'manager_name' , 'manager_phone' , 'created_at', 'updated_at',
'show_atg_dpk','show_atg_ago','show_atg_pms','show_fcc_dpk','show_fcc_ago','show_fcc_pms','show_atg_data','show_fcc_data','hasFCC','hasATG','regionid','fcc_oem','atg_oem','daily_pms_target','daily_ago_target','daily_dpk_target','daystodelivery','oem_stationid', 'v1_id', 'start_date', 'pc_approval_levels', 'code', 'approved_dpk_tolerance', 'approved_ago_tolerance', 'approved_pms_tolerance', 'fcc_active', 'atg_active'
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
    public function station_regions(){
        return $this->hasMany(StationRegions::class);
    }
    public function station_users(){
        return $this->hasMany(StationUsers::class);
    }

    public function tanks(){
        return $this->hasMany(Tanks::class);
    }

    public function product_prices(){
        return $this->hasMany(ProductPrices::class);
    }
}

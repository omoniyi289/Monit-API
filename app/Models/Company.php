<?php

namespace App;

//use Core\Models\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    protected $fillable = [
        'name', 'email', 'registration_number', 'country', 'state', 'city',
        'address', 'user_id', 'created_at', 'updated_at', 'v1_id', 'v1_user_id', 'company_type', 'sms_sender_id'
    ];

    public function users() {
        return $this->hasMany(StationUsers::class);
    }

    public function user_companies(){
        return $this->hasOne(User::class);
    }

    public function stations() {
        return $this->hasMany(Station::class);
    }

    public function tank_groups() {
        return $this->hasMany(TankGroups::class);
    }

    public function product_prices(){
        return $this->hasMany(ProductPrices::class);
    }

    public function logger($action, $user_id = null, $comment = null, $subject = null, $subject_id = null)
    {
        return $this;
    }
}

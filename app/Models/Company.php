<?php

namespace App;

//use Core\Models\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\CompanyPermission;
use App\Models\CompanyNotification;

class Company extends Model
{
    protected $fillable = [
        'name', 'email', 'registration_number', 'country', 'state', 'city',
        'address', 'user_id', 'created_at', 'updated_at', 'v1_id', 'v1_user_id', 'company_type', 'sms_sender_id', 'active'
    ];

    public function users() {
        return $this->hasMany(StationUsers::class);
    }
    public function company_permissions(){
        return $this->hasMany(CompanyPermission::class,"company_id", "id");
    } 
     public function company_notifications(){
        return $this->hasMany(CompanyNotification::class,"company_id", "id");
    } 
    public function user_companies(){
        return $this->hasOne(User::class);
    }

    public function stations() {
        return $this->hasMany(Station::class, 'company_id', 'id');
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

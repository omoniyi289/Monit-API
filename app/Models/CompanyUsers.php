<?php

namespace App;

//use Core\Models\Model;
use Illuminate\Database\Eloquent\Model;
class CompanyUsers extends Model
{
	
    protected $fillable = [
        'fullname','username', 'email', 'password', 'phone_number','company_id',
        'type','is_password_reset',
    ];

    public function companies() {
        return $this->belongsTo(Company::class,'company_id');
    }
    public function station() {
        return $this->belongsTo(Company::class,'station_id');
    }


}

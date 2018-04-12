<?php

/*namespace App;

//use Core\Models\Model;
use Illuminate\Database\Eloquent\Model;
class CompanyUsers extends Model
{
	
    protected $fillable = [
        'fullname','username', 'email', 'password', 'phone_number','company_id'
    ];

    public function companies() {
        return $this->belongsTo(Company::class,'company_id');
    }

//public function role() {
  //      return $this->belongsTo(App\Models\CompanyUserRole::class,'company_user_id');
   // }


}

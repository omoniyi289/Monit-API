<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\Permission;
use App\Company;

class CompanyPermission extends Model
{
    protected $fillable = [
        'permission_id','role_id', 'company_id'
    ];

   
 protected $table = 'company_permissions';

   public function permission(){
    	return $this->hasOne(Permission::class, "id", 'permission_id');
    } 
    public function company(){
        return $this->belongsTo(Company::class,"company_id");
    }  
     
}
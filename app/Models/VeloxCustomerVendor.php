<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Company;
use App\Models\VeloxCustomer;
use App\Models\VeloxCustomerWallet;


class VeloxCustomerVendor extends Model
{
	protected $connection = 'mysql2';
  protected $table = 'company_vendors';
  protected $fillable = [
        'vendor_id', 'company_id', 'status', 'updated_at', 'partnership_code'
    ];

  public function wallet(){
    	return $this->hasMany(VeloxCustomerWallet::class,'company_id', 'company_id');
   } 
    
  public function customer(){
        return $this->belongsTo(VeloxCustomer::class,"company_id");
    }  
     
}
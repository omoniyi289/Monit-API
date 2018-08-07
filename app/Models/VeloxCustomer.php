<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\VeloxCustomerWallet;


class VeloxCustomer extends Model
{
	protected $connection = 'mysql2';
	protected $table = 'companies';
    protected $fillable = [
        'name', 'email', 'registration_number', 'country', 'state', 'city',
        'address', 'user_id', 'created_at', 'updated_at', 'v1_id', 'v1_user_id', 'company_type'
    ];

  // public function wallet(){
  //       return $this->hasMany(VeloxCustomerWallet::class, "company_id", "id");
  //   }  
}

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\VeloxCustomerWallet;


class VeloxVendor extends Model
{
	protected $connection = 'mysql2';
	protected $table = 'vendors';
    protected $fillable = [
        'name', 'email', 'phone', 'state', 'city',
        'address', 'user_id', 'created_at', 'updated_at', 'v1_id', 'v1_user_id', 'company_type'
    ];

  
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VeloxCustomerWallet extends Model
{	 
	 protected $connection = 'mysql2';
	 protected $table = 'company_wallet';
	 protected $fillable = ['vendor_id','company_id','current_balance','current_credit_limit'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VeloxPaymentHistory extends Model
{
	 protected $connection = 'mysql2';
	 protected $table = 'payment_history';
	 protected $fillable = ['vendor_id','company_id','amount_paid','start_company_balance','end_company_balance','current_credit_limit','payment_made_by','payment_uploaded_by', 'payment_date'];


}

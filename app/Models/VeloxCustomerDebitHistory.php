<?php
namespace App\Models;

use App\Tanks;
use Illuminate\Database\Eloquent\Model;

class VeloxCompanyDebitHistory extends Model
{	 
	 protected $connection = 'mysql2';
	 protected $table = 'company_debit_history';
	 protected $fillable = ['vendor_id','company_id','fuel_purchase_transaction_id','start_company_balance','end_company_balance','current_credit_limit'];

}

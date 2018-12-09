<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Company;
use App\User;
class CompanyBankAccount extends Model
{
    //
    protected $table = 'company_bank_accounts';
    protected $fillable = ['account_number','bank', 'company_id'];

 
     public function company(){
        return $this->belongsTo(Company::class, 'company_id');
    }
}

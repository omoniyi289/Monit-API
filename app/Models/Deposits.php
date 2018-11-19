<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Station;
use App\User;
class Deposits extends Model
{
    //
    protected $table = 'cash_bank_deposits';
    protected $fillable = ['date','created_by', 'payment_type', 'pos_receipt_number', 'pos_receipt_range','amount','teller_number','station_id', 'company_id', 'account_number', 'bank','verified_by','v1_id', 'teller_date', 'reading_date','created_at','upload_type', 'note'
    ];

    public function station(){
        return $this->belongsTo(Station::class, 'station_id');
    }

     public function creator(){
        return $this->belongsTo(User::class, 'created_by');
    }
     public function approver(){
        return $this->belongsTo(User::class, 'verified_by');
    }
}

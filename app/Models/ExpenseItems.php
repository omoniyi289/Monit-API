<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Station;
class ExpenseItems extends Model
{
    //
    protected $fillable = ['expense_id','created_by','unit_amount','total_amount','quantity','expense_type','proof_of_payment','approved','item_code','item_description','v1_id', 'created_at'
    ];

    public function station(){
        return $this->belongsTo(Station::class, 'station_id');
    }

}

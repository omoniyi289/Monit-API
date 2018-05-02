<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Station;
class ExpenseHeader extends Model
{
    //
    protected $table = 'expense_header';
    protected $fillable = ['expense_code','created_by','company_id','station_id','expense_date','total_amount','v1_id', 'created_at'
    ];

    public function station(){
        return $this->belongsTo(Station::class, 'station_id');
    }

}

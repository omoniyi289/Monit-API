<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Station;
class Expenses extends Model
{
    //
    protected $fillable = ['date','created_by', 'expense_type', 'amount','description','station_id', 'company_id', 'expense_code','v1_id'
    ];

    public function station(){
        return $this->belongsTo(Station::class, 'station_id');
    }

}

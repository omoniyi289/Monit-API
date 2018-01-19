<?php

namespace App;

use Core\Models\Model;

class ProductChangeLogs extends Model
{
    //
    protected $fillable = [
        'old_price_tag','new_price_tag', 'company_id', 'product_id' , 'updated_by',
        'approved_by','station_id', 'is_approved'
    ];
}

<?php

namespace App;

use Core\Models\Model;

class Products extends Model
{
    protected $fillable = [
        'name','code'
    ];

    public function tanks(){
        return $this->hasMany(Tanks::class);
    }
}

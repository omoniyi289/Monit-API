<?php

namespace App\Models;

//use Core\Models\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FGDemoCompany extends Model
{
	protected $table = 'FGdemo_companies';
    protected $fillable = [
        'name', 'email', 'registration_number', 'country', 'state', 'city',
        'address', 'user_id', 'created_at', 'updated_at', 'v1_id', 'v1_user_id', 'company_type', 'id'
    ];

    public function stations() {
        return $this->hasMany(FGDemoStation::class);
    }

}

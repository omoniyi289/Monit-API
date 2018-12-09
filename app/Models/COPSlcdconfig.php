<?php

namespace App\Models;
use App\User;
use App\Station;
use App\Company;
//use Core\Models\Model;
use Illuminate\Database\Eloquent\Model;

class COPSlcdconfig extends Model
{
    //
    protected $table = 'cops_lcd_config';
    protected $fillable = ['company_id','status', 'name', 'type', 'id'];


	public function station() {
        return $this->belongsTo(Station::class,'station_id');
    }
    public function company() {
        return $this->belongsTo(Company::class,'company_id');
    }
    public function uploader() {
        return $this->belongsTo(User::class,'uploaded_by');
    }
}

<?php

namespace App\Models;
use App\User;
use App\Station;
use App\Company;
//use Core\Models\Model;
use Illuminate\Database\Eloquent\Model;

class COPS extends Model
{
    //
    protected $table = 'cops';
    protected $fillable = ['company_id','station_id','uploaded_by','survey_date','location','competitor','d2d','omp_pms','company_pms','omp_ago','company_ago','omp_dpk','company_dpk','omp_lube','company_lube','omp_lpg','company_lpg','note'];


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

<?php

namespace App\Models;
use App\User;
use App\Station;
//use Core\Models\Model;
use Illuminate\Database\Eloquent\Model;

class ROPS extends Model
{
    //
    protected $table = 'rops';
    protected $fillable = ['company_id','station_id',
                           'uploaded_by','survey_date','pc1_name','pc2_name','pc3_name','pc1_price_pms','pc2_price_pms','pc3_price_pms','pc1_price_ago','pc2_price_ago','pc3_price_ago','pc1_price_dpk','pc2_price_dpk','pc3_price_dpk','sc1_name','sc2_name','sc3_name','sc1_price_pms','sc2_price_pms','sc3_price_pms','sc1_price_ago','sc2_price_ago','sc3_price_ago','sc1_price_dpk','sc2_price_dpk','sc3_price_dpk','nearest_depot_name','nearest_depot_pms','nearest_depot_ago','nearest_depot_dpk','recommended_selling_price_pms','recommended_selling_price_ago','recommended_selling_price_dpk','current_selling_price_pms','current_selling_price_ago','current_selling_price_dpk'];


	public function station() {
        return $this->belongsTo(Station::class,'station_id');
    }
    public function uploader() {
        return $this->belongsTo(User::class,'uploaded_by');
    }
}

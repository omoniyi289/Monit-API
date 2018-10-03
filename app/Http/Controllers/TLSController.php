<?php
namespace App\Http\Controllers;

use App\Requests\ApiItemVariantRequest;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Services\MigrationService;
use Illuminate\Support\Facades\DB;

class TLSController extends BaseController
{

    public function __construct()
    {
    }
 
   
    public function get_raw() {
    return DB::select('select read_at, db_fill_time, log from atg_readings order by id desc');

    }
    public function get_today() {
        
    return DB::select('select read_at, db_fill_time, log from atg_readings where (DATE(db_fill_time) = ?  order by id desc)',  [date("Y-m-d") ]);
    }   
}
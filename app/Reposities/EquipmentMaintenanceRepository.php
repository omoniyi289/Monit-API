<?php
/**
 * Created by PhpStorm.
 * User: omoniyiomotoso
 * Date: 1/12/18
 * Time: 6:45 PM
 */

namespace App\Reposities;
use Illuminate\Support\Facades\DB;
use App\User;
use Core\Repository\BaseRepository;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class EquipmentMaintenanceRepository 
{

public function create(array $data){

   $client = new Client(); //GuzzleHttp\Client
    $response = $client->request('POST',env('VELOX_API_URL').'/sm_manage_creditlimits',[
        'form_params' => $data ] 
        );
     $result= json_decode($response->getBody()->getContents());
     return $result;
    }

public function update(array $data){

   $client = new Client(); //GuzzleHttp\Client
    $response = $client->request('PATCH',env('VELOX_API_URL').'/sm_manage_creditlimits/'.$data['customer_creditlimit']['id'],[
        'form_params' => $data ] 
        );
     $result= json_decode($response->getBody()->getContents());
     return $result;
    }

   public function get_pump_readings($station_id, $start_date, $end_date){
//added NOT EQUAL TO 0 to avoid situations where 0 was entered for both opening and closing of totalizer readings
    $result = DB::select('select daily_totalizer_readings.company_id, station_id, stations.name as station_name, daily_totalizer_readings.nozzle_code, min(open_shift_totalizer_reading) as min_reading, 
max(close_shift_totalizer_reading)  as max_reading, max(close_shift_totalizer_reading) - min(open_shift_totalizer_reading) as total_sales
from station_manager.daily_totalizer_readings
left join stations on stations.id = daily_totalizer_readings.station_id
where daily_totalizer_readings.station_id = ? and  daily_totalizer_readings.reading_date >= ?
 and  daily_totalizer_readings.reading_date <= ? and  daily_totalizer_readings.open_shift_totalizer_reading != ?  group by daily_totalizer_readings.company_id, daily_totalizer_readings.station_id, daily_totalizer_readings.nozzle_code,stations.name', [$station_id, $start_date, $end_date, 0]);


     return $result;
    }
}
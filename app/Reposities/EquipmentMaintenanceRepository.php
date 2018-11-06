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


public function get_station_pumps_readings($station_id, $start_date, $end_date){
//added NOT EQUAL TO 0 to avoid situations where 0 was entered for both opening and closing of totalizer readings
    $result = DB::select('select daily_totalizer_readings.company_id, pumps.station_id, stations.name as station_name, pumps.pump_nozzle_code as nozzle_code, min(close_shift_totalizer_reading) as min_reading, 
max(close_shift_totalizer_reading)  as max_reading, max(close_shift_totalizer_reading) - min(close_shift_totalizer_reading) as total_sales from station_manager.pumps left join station_manager.daily_totalizer_readings on pumps.id = daily_totalizer_readings.pump_id 
left join stations on stations.id = daily_totalizer_readings.station_id
where daily_totalizer_readings.station_id = ? and  daily_totalizer_readings.reading_date >= ?
 and  daily_totalizer_readings.reading_date <= ? and  daily_totalizer_readings.open_shift_totalizer_reading != ?  group by daily_totalizer_readings.company_id, pumps.station_id, pumps.pump_nozzle_code,stations.name', [$station_id, $start_date, $end_date, 0]);
     return $result;
    }

public function get_pump_readings($pump_id, $start_date, $end_date){
//added NOT EQUAL TO 0 to avoid situations where 0 was entered for both opening and closing of totalizer readings
    $result = DB::select('select daily_totalizer_readings.company_id, station_id, stations.name as station_name, daily_totalizer_readings.nozzle_code, min(close_shift_totalizer_reading) as min_reading, 
max(close_shift_totalizer_reading)  as max_reading, max(close_shift_totalizer_reading) - min(close_shift_totalizer_reading) as total_sales
from station_manager.daily_totalizer_readings
left join stations on stations.id = daily_totalizer_readings.station_id
where daily_totalizer_readings.pump_id = ? and  daily_totalizer_readings.reading_date >= ?
 and  daily_totalizer_readings.reading_date <= ? and  daily_totalizer_readings.open_shift_totalizer_reading != ?  group by daily_totalizer_readings.company_id, daily_totalizer_readings.station_id, daily_totalizer_readings.nozzle_code,stations.name', [$pump_id, $start_date, $end_date, 0]);
     return $result;
    }

public function get_pump_readings_from_inception($pump_id){
//added NOT EQUAL TO 0 to avoid situations where 0 was entered for both opening and closing of totalizer readings
    $result = DB::select('select daily_totalizer_readings.company_id, station_id, stations.name as station_name, daily_totalizer_readings.nozzle_code, min(close_shift_totalizer_reading) as min_reading,min(reading_date) as min_date, 
max(close_shift_totalizer_reading)  as max_reading, max(close_shift_totalizer_reading) - min(close_shift_totalizer_reading) as total_sales
from station_manager.daily_totalizer_readings
left join stations on stations.id = daily_totalizer_readings.station_id
where daily_totalizer_readings.pump_id = ? and  daily_totalizer_readings.open_shift_totalizer_reading != ?  group by daily_totalizer_readings.company_id, daily_totalizer_readings.station_id, daily_totalizer_readings.nozzle_code,stations.name', [$pump_id, 0]);
     return $result;
    }

}
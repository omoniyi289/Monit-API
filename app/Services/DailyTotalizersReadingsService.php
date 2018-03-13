<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 2:17 AM
 */

namespace App\Services;

use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use App\Models\DailyTotalizerReadings;
class DailyTotalizersReadingsService
{
    private $database;

    public function __construct(DatabaseManager $database)
    {
        $this->database = $database;
    }
    public function create(array $data) {
        $this->database->beginTransaction();
        try{
            foreach ($data['readings'] as $value) {
                    $pump = DailyTotalizerReadings::create(['company_id' => $data['company_id'], 'station_id' => $data['station_id'], 'pump_id' => $value['pump_id'], 'nozzle_code' => $value['nozzle_code'],'pump_number' => $value['pump_number'], 'open_shift_totalizer_reading' => $value['opening_reading'],'created_by' => $data['created_by'], 'status' =>'Opened']);
                }
            
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $pump;
    }
     public function update(array $data)
    {
        
        $this->database->beginTransaction();
        try {
             foreach ($data['readings'] as $value) {
        $single_pump = DailyTotalizerReadings::where('created_at', 'LIKE', date("Y-m-d").'%')->where('pump_id', $value['pump_id'])->update(['company_id' => $data['company_id'], 'station_id' => $data['station_id'], 'pump_id' => $value['pump_id'], 'nozzle_code' => $value['nozzle_code'],'shift_1_cash_collected' => $value['cash_collected'], 'shift_1_totalizer_reading' => $value['closing_reading'],'created_by' => $data['created_by'], 'status' =>'Closed']);
                }

            // DailyTotalizerReadings::update($stock, $data);
        } catch (Exception $exception) {
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $data;
    }

    public function get_all(array $options = []){
        return DailyTotalizerReadings::all();
    }
    public function get_by_id($stock_id, array $options = [])
    {
        return $this->get_requested_stock($stock_id);
    }
   //   public function get_by_station_id($stock_id)
    //{
      // return DailyTotalizerReadings::where('station_id',$stock_id)->get();
    //}
      public function get_by_params($params)
    {   

       $result = DailyTotalizerReadings::where('station_id',$params['station_id']);
       if(isset($params['date'])){
            $result->where('created_at', 'LIKE', $params['date'].'%');
       }
       else{
        $result->where('created_at', 'LIKE', date('Y-m-d').'%');
       }
       return $result->get();
    }
    private function get_requested_stock($stock_id, array $options = [])
    {
        return DailyTotalizerReadings::where('id', $stock_id)->get();
    }
}
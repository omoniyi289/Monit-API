<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 2:17 AM
 */

namespace App\Services;


use App\Reposities\CompanyRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use App\Models\DailyStockReadings;
use App\Tanks;
class DailyStockReadingsService
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
                //to avoid double entry
                $present = DailyStockReadings::where('tank_id', $value['tank_id'])->where('reading_date', 'LIKE', "%".date_format(date_create($data['reading_date']),"Y-m-d")."%")->get();
                if(count($present) > 0){
                        continue;
                    }
                //else continue insert
                    $stock = DailyStockReadings::create(['company_id' => $data['company_id'], 'station_id' => $data['station_id'], 'tank_id' => $value['tank_id'],'tank_code' => $value['tank_code'], 'phy_shift_start_volume_reading' => $value['opening_reading'],'created_by' => $data['created_by'],'reading_date' => date_format(date_create($data['reading_date']),"Y-m-d").' 00:00:00', 'status' =>'Opened', 'product'=> $value['product']]);
                }
            
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $stock;
    }
     public function update(array $data)
    {
        $this->database->beginTransaction();
        try {
           
            foreach ($data['readings'] as $value) {
                    if($value['status'] == 'Closed'){
                    $stock = DailyStockReadings::where('reading_date', $data['reading_date'])->where('tank_id', $value['tank_id'])->update(['phy_shift_end_volume_reading' => $value['closing_reading'],'return_to_tank'=>$value['rtt'],'end_delivery'=>$value['qty_received'] ,'status' =>'Closed']);
                    }else if($value['status'] == 'Modified'){
                    $stock = DailyStockReadings::where('reading_date', $value['reading_date'])->where('tank_id', $value['tank_id'])->update(['phy_shift_end_volume_reading' => $value['closing_reading'],'phy_shift_start_volume_reading' => $value['opening_reading'],'return_to_tank'=>$value['rtt'],
                        'end_delivery'=>$value['qty_received'],'last_modified_by'=>$data['last_modified_by']]);
              }
              }  
        } catch (Exception $exception) {
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $stock;
    }

    public function get_all(array $options = []){
        return DailyStockReadings::all();
    }
     public function get_filtered($company_id, $station_id){
        $query = DailyStockReadings::with('tank.product');
        if($company_id != 'all'){
            $query = $query->where('company_id', $company_id);
        }
        if($station_id != 'all'){
            $query = $query->where('station_id', $station_id);
        }
        return $query->get();
    }
    public function get_by_id($stock_id, array $options = [])
    {
        return $this->get_requested_stock($stock_id);
    }
      public function get_by_params($params)
    {   

       $result = DailyStockReadings::where('station_id',$params['station_id']);
       //return date_format(date_create($params['date']),"Y-m-d");
       if(isset($params['date'])){
            $result->where('reading_date', 'LIKE', date_format(date_create($params['date']),"Y-m-d").'%');
             return $result->get();
       }else if(isset($params['opening_station'])){
            $tanks = Tanks::where('station_id',$params['station_id'])->with('product')->orderBy('code', 'ASC')->get();
            foreach ($tanks as $key => $value) {
            ////get the last input date
            $last_reading = DailyStockReadings::select('id','phy_shift_end_volume_reading')->where('tank_id',$value['id'])->orderBy('reading_date', 'desc')->get()->first();
              $tanks[$key]['last_closing_reading'] = $last_reading['phy_shift_end_volume_reading'];
        }
     
            return $tanks;
       }
       else{
            ////get the last input date
            $timecheck = DailyStockReadings::where('station_id',$params['station_id'])->orderBy('reading_date', 'desc')->get()->first();
            $result->where('reading_date', 'LIKE',"%".date_format(date_create($timecheck['reading_date']),"Y-m-d")."%");
            $result->orderBy('reading_date', 'desc');
             return $result->get();
           }
      
    }
  
       public function close_station($params)
    {   

       $result = DailyStockReadings::where('station_id',$params['station_id']);

        $timecheck = DailyStockReadings::where('station_id',$params['station_id'])->orderBy('reading_date', 'desc')->get()->first();
        $result->where('reading_date', 'LIKE', "%".date_format(date_create($timecheck['reading_date']),"Y-m-d")."%");
        $result->orderBy('reading_date', 'desc');
       
       return $result->get();
    }
    private function get_requested_stock($stock_id, array $options = [])
    {
        return DailyStockReadings::where('id', $stock_id)->get();
    }
}
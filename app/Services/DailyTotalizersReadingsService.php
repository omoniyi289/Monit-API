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
use App\Pumps;
use App\ProductPrices;
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
          //return $data['created_at'];
            foreach ($data['readings'] as $value) {
                    //to avoid double entry
                  $present = DailyTotalizerReadings::where('pump_id', $value['pump_id'])->where('reading_date', $data['reading_date'])->get();
                  if(count($present) > 0){
                          continue;
                      }
                  //else continue insert
                    $pump = DailyTotalizerReadings::create(['company_id' => $data['company_id'], 'station_id' => $data['station_id'], 'pump_id' => $value['pump_id'], 'nozzle_code' => $value['pump_nozzle_code'], 'open_shift_totalizer_reading' => $value['opening_reading'],'created_by' => $data['created_by'], 'status' =>'Opened', 'reading_date'=> $data['reading_date'], 'product'=> $value['product']]);
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
         if($value['status'] == 'Shift End'){   
         $pump = Pumps::where('id', $value['pump_id'])->with('product')->get()->first();

         $price = ProductPrices::where('product_id', $pump['product']['id'])->where('station_id', $data['station_id'])->get()->first(); 
            if($data['shift_batch']== 'First Shift'){
               $single_pump = DailyTotalizerReadings::where('reading_date', $data['reading_date'])->where('pump_id', $value['pump_id'])->update(['shift_1_cash_collected' => $value['cash_collected'],'ppv'=>$price['new_price_tag'], 'shift_1_totalizer_reading' => $value['closing_reading'],'status' =>$value['status']]);
            }else if($data['shift_batch']== 'Second Shift'){
              $single_pump = DailyTotalizerReadings::where('reading_date', $data['reading_date'])->where('pump_id', $value['pump_id'])->update(['shift_2_cash_collected' => $value['cash_collected'],'ppv'=>$price['new_price_tag'], 'shift_2_totalizer_reading' => $value['closing_reading'],'status' =>$value['status']]);
            }
              }
              else if($value['status']== 'Closed'){
                //it is closed
                $pump = Pumps::where('id', $value['pump_id'])->with('product')->get()->first();

                $price = ProductPrices::where('product_id', $pump['product']['id'])->where('station_id', $data['station_id'])->get()->first();

               $single_pump = DailyTotalizerReadings::where('reading_date', $data['reading_date'])->where('pump_id', $value['pump_id'])->update(['cash_collected' => $value['cash_collected'],'ppv'=>$price['new_price_tag'],'close_shift_totalizer_reading' => $value['closing_reading'], 'status' =>$value['status']]);
              }
              else if($value['status'] == 'Modified'){
                $single_pump = DailyTotalizerReadings::where('reading_date', $value['reading_date'])->where('pump_id', $value['pump_id'])->update(['cash_collected' => $value['cash_collected'],'shift_1_cash_collected' => $value['first_shift_cash_collected'],'shift_2_cash_collected' => $value['second_shift_cash_collected'],'ppv' => $value['ppv'], 'open_shift_totalizer_reading' => $value['opening_reading'],'shift_1_totalizer_reading' => $value['first_shift_reading'],'shift_2_totalizer_reading' => $value['second_shift_reading'],'close_shift_totalizer_reading' => $value['closing_reading'],'last_modified_by'=>$data['last_modified_by']]);
              }
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
    public function get_filtered($company_id, $station_id){
        //return DailyTotalizerReadings::with('pump.product')->get();
        $query = DailyTotalizerReadings::with('pump.product');
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
   //   public function get_by_station_id($stock_id)
    //{
      // return DailyTotalizerReadings::where('station_id',$stock_id)->get();
    //}
      public function get_by_params($params)
    {   

       $result = DailyTotalizerReadings::where('station_id',$params['station_id']);
       if(isset($params['date'])){
            $result->where('reading_date', 'LIKE', date_format(date_create($params['date']),"Y-m-d").'%');
             return $result->get();
       }else if(isset($params['opening_station'])){
            $pumps = Pumps::where('station_id',$params['station_id'])->with('product')->orderBy('pump_nozzle_code', 'ASC')->get();
            foreach ($pumps as $key => $value) {
            ////get the last input date
            $last_reading = DailyTotalizerReadings::select('id','close_shift_totalizer_reading')->where('pump_id',$value['id'])->orderBy('reading_date', 'desc')->get()->first();
              $pumps[$key]['last_closing_reading'] = $last_reading['close_shift_totalizer_reading'];
        }
     
            return $pumps;
       }
       else{
        $timecheck = DailyTotalizerReadings::where('station_id',$params['station_id'])->orderBy('id', 'desc')->get()->first();
        $result->where('reading_date', 'LIKE',"%".date_format(date_create($timecheck['reading_date']),"Y-m-d")."%");
        $result->orderBy('id', 'desc');
         return $result->get();
       }
       
    }
    private function get_requested_stock($stock_id, array $options = [])
    {
        return DailyTotalizerReadings::where('id', $stock_id)->get();
    }
}
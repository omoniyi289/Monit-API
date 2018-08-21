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
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Station;
use App\Services\StationService;
use Maatwebsite\Excel\Facades\Excel;

class DailyTotalizersReadingsService
{
    private $database;
    private $station_service;

    public function __construct(DatabaseManager $database, StationService $station_service)
    {
        $this->database = $database;
        $this->station_service = $station_service;
        $this->csv_error_log = array();
        $this->csv_success_rows = array();
        $this->user_station_ids = array();
        $this->current_user = array();
    }
    public function create(array $data) {
        $this->database->beginTransaction();
        try{
          //return $data['created_at'];
            foreach ($data['readings'] as $value) {
                    //to avoid double entry
                  $present = DailyTotalizerReadings::where('pump_id', $value['pump_id'])->where('reading_date', 'LIKE', "%".date_format(date_create($data['reading_date']),"Y-m-d")."%")->get();
                  if(count($present) > 0){
                          continue;
                      }
                  //else continue insert
                    $pump = DailyTotalizerReadings::create(['company_id' => $data['company_id'], 'station_id' => $data['station_id'], 'pump_id' => $value['pump_id'], 'nozzle_code' => $value['pump_nozzle_code'], 'open_shift_totalizer_reading' => $value['opening_reading'],'created_by' => $data['created_by'], 'status' =>'Opened', 'reading_date'=> date_format(date_create($data['reading_date']),"Y-m-d").' 00:00:00', 'product'=> $value['product']]);
                }
            
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $pump;
    }

    public function upload_parsed_csv_data(array $data) {
        $this->database->beginTransaction();
        //return $data;
        try{
                foreach ($data['readings'] as $value) {
                    $company_id = $value['company_id'];
                    $station_id = $value['station_id'];
                    $pump_id = $value['pump_id'];
                    $nozzle_code = $value['pump_nozzle_code'];
                    $opening_totalizer = isset($value['opening_totalizer']) ? $value['opening_totalizer'] : 0;
                    $closing_totalizer = isset($value['closing_totalizer']) ? $value['closing_totalizer'] : 0;
                    $created_by = $data['last_modified_by'];
                    $reading_date = $value['date'];
                    $status = 'Closed'; 
                    $product = $value['product'];
                    $ppv = isset($value['ppv']) ? $value['ppv'] : 0;
                    $cash_collected = isset($value['cash_collected']) ? $value['cash_collected'] : 0;
                    $last_modified_by = $data['last_modified_by'];

                    //to avoid double entry
                    $present = DailyTotalizerReadings::where('pump_id', $pump_id)->where('reading_date', 'LIKE', "%".date_format(date_create($reading_date),"Y-m-d")."%")->get();
                    if(count($present) > 0){
                            continue;
                        }
                    //else continue insert
                        $stock = DailyTotalizerReadings::create(['company_id' => $company_id, 'station_id' => $station_id, 'pump_id' => $pump_id,'nozzle_code' => $nozzle_code, 'open_shift_totalizer_reading' => $opening_totalizer, 'close_shift_totalizer_reading' => $closing_totalizer,'created_by' => $created_by,'reading_date' => date_format(date_create($reading_date),"Y-m-d").' 00:00:00', 'status' =>$status, 'product'=> $product,'ppv'=>$ppv,
                            'cash_collected'=>$cash_collected,'last_modified_by'=>$last_modified_by ]);
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

    public function handle_file_upload($request)
    {
      $this->current_user = JWTAuth::parseToken()->authenticate();
      $user_id = $this->current_user->id;

        if($request->hasFile('file')) {

            $fileItself = $request->file('file');
            $rows = array();
            $load = Excel::load($fileItself, function($reader) {})->get();
            $row = $load[0];
            if(!isset($row->station_name)){
                array_push($this->csv_error_log , ["message" => "Station Name column not specified"]);
            }else if(!isset($row->pump_nozzle_code)){
                array_push($this->csv_error_log , ["message" => "Nozzle Code column not specified"]);
            }else if(!isset($row->date)){
                array_push($this->csv_error_log , ["message" => "Date column not specified"]);
            }else{
              //to verify if user has access to upload for that station
               $user_stations_details = $this->station_service->get_stations_by_user_id($user_id);
               foreach ($user_stations_details as $key => $value) {
                  array_push($this->user_station_ids, $value['station_id']);
               }

               ///validate station, tank_code and reading_dae
                foreach($load as $key => $row) {
                $this->validate_station_pump_code_and_upload_date($key, $row);
                }
            }
        }
        return  array(['error' => $this->csv_error_log, 'success' => $this->csv_success_rows]);
    }

    public function get_by_id($stock_id, array $options = [])
    {
        return $this->get_requested_stock($stock_id);
    }
  
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
    private function validate_station_pump_code_and_upload_date($key, $row){
     
        $station_details  = Station::where('name', $row['station_name'])->get(['id', 'company_id'])->first();
        $real_key = (int)$key+1;
        if(count($station_details) == 0){
            array_push( $this->csv_error_log, ["message" => "Station ". $row['station_name']. " on row ".$real_key." not found, please confirm station name (check spelling)" ] );
        }else if($this->current_user->company_id != 'master' and !in_array($station_details['id'], $this->user_station_ids)){
            array_push($this->csv_error_log, ["message" => "You are not permitted to upload readings for ". $row['station_name']. " on row ".$real_key ]);
        }else{
            $row['station_id'] = $station_details['id'];
            $row['company_id'] = $station_details['company_id'];
            $pump_details  = Pumps::with('product:id,code')->where('pump_nozzle_code', $row['pump_nozzle_code'])->where('station_id', $station_details['id'])->get(['id','product_id'])->first();

            if(count($pump_details) == 0){
                array_push($this->csv_error_log , ["message" => $row['pump_nozzle_code']. " on row ".$real_key." not found for  station ".$row['station_name']. " please confirm nozzle code (check spelling)"]);
            }else{
                $row['pump_id'] = $pump_details['id'];
                $row['product'] = $pump_details->product['code'];
                $date = "%".date_format(date_create($row['date']),"Y-m-d")."%";
                $readings_details  = DailyTotalizerReadings::where('nozzle_code', $row['pump_nozzle_code'])->where('station_id', $station_details['id'])->where('reading_date','LIKE', $date)->get(['id'])->first();
                if(count($readings_details) > 0){
                    array_push($this->csv_error_log , ["message" => "Reading already exist for ". $row['pump_nozzle_code']. " on row ".$real_key." please contact admin to modify, delete the row for now"]);
                }else{
                   array_push($this->csv_success_rows, $row);
                } 
            }
        }
        
    }
}
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
use App\Models\DailyTotalizerReadings;
use App\Models\Deposits;
use App\Tanks;
use Maatwebsite\Excel\Facades\Excel;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Station;


class DailyStockReadingsService
{
    private $database;

    public function __construct(DatabaseManager $database, StationService $station_service)
    {
        $this->database = $database;
        $this->csv_error_log = array();
        $this->station_service = $station_service;
        $this->csv_success_rows = array();
        $this->user_station_ids = array();
        $this->current_user = array();
    }
    public function create(array $data) {
        $this->database->beginTransaction();
        $stock = '';
        try{
            if( count($data['readings']) < 1 ){
                return 'invalid_input';
            }
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
    public function upload_parsed_csv_data(array $data) {
        $this->database->beginTransaction();
        //return $data;
        try{
                foreach ($data['readings'] as $value) {
                    $company_id = $value['company_id'];
                    $station_id = $value['station_id'];
                    $tank_id = $value['tank_id'];
                    $tank_code = $value['tank_code'];
                    $git_loss = $value['git_loss'];
                    $phy_shift_start_volume_reading = isset($value['opening_dip']) ? $value['opening_dip'] : 0;
                    $phy_shift_end_volume_reading = isset($value['closing_dip']) ? $value['closing_dip'] : 0;
                    $created_by = $data['last_modified_by'];
                    $reading_date = $value['date'];
                    $status = 'Closed'; 
                    $product = $value['product'];
                    $return_to_tank = isset($value['rtt']) ? $value['rtt'] : 0;
                    $end_delivery = isset($value['delivery']) ? $value['delivery'] : 0;
                    $last_modified_by = $data['last_modified_by'];

                    //to avoid double entry
                    $present = DailyStockReadings::where('tank_id', $tank_id)->where('reading_date', 'LIKE', "%".date_format(date_create($reading_date),"Y-m-d")."%")->get();
                    if(count($present) > 0){
                            continue;
                        }
                    //else continue insert
                        $stock = DailyStockReadings::create(['company_id' => $company_id, 'station_id' => $station_id, 'tank_id' => $tank_id,'tank_code' => $tank_code, 'phy_shift_start_volume_reading' => $phy_shift_start_volume_reading, 'phy_shift_end_volume_reading' => $phy_shift_end_volume_reading,'created_by' => $created_by,'reading_date' => date_format(date_create($reading_date),"Y-m-d").' 00:00:00', 'status' =>$status, 'product'=> $product,'return_to_tank'=>$return_to_tank,'git_loss' =>$git_loss,
                            'end_delivery'=>$end_delivery,'last_modified_by'=>$last_modified_by ]);
                    }
            
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $stock;
    }
    public function bovas_upload_parsed_csv_data(array $data) {
        $this->database->beginTransaction();
        //return $data;
        try{
                foreach ($data['readings'] as $value) {
                    $company_id = $value['company_id'];
                    $station_id = $value['station_id'];
                    //$tank_id = $value['tank_id'];
                    //code is product
                    $tank_code = $value['product'];
                    $phy_shift_start_volume_reading = isset($value['opening_dip']) ? $value['opening_dip'] : 0;
                    $phy_shift_end_volume_reading = isset($value['closing_dip']) ? $value['closing_dip'] : 0;
                    $created_by = $data['last_modified_by'];
                    $reading_date = $value['date'];
                    $status = 'Closed'; 
                    $ppv = $value['ppv'];
                    $git_loss = $value['git_loss'];
                    $product = $value['product'];
                    $opening_totalizer = 0;
                    

                    $return_to_tank = isset($value['rtt']) ? $value['rtt'] : 0;
                    $end_delivery = isset($value['delivery']) ? $value['delivery'] : 0;
                    $closing_totalizer = $phy_shift_start_volume_reading - $phy_shift_end_volume_reading + $end_delivery + $return_to_tank;
                    $last_modified_by = $data['last_modified_by'];
                    //get a registered tank to attach the readings to
                    $tank_info = Tanks::where('code','LIKE', '%'.$product.'%')->where('station_id', $station_id)->get()->first();
                        $tank_id = 9;
                        if( count($tank_info) == 1){
                        $tank_id = $tank_info['id'];
                        $tank_code = $tank_info['code'];
                            }
                    //delete previous entry for the set date
                    $present = DailyStockReadings::where('tank_code', $tank_code)->where('station_id', $station_id)->where('reading_date', 'LIKE', "%".date_format(date_create($reading_date),"Y-m-d")."%")->delete();

                    $present_2 = DailyTotalizerReadings::where('nozzle_code', $product)->where('station_id', $station_id)->where('reading_date', 'LIKE', "%".date_format(date_create($reading_date),"Y-m-d")."%")->delete();

                   // if(count($present) == 0){
     
                    //else continue insert
                        

                        $stock = DailyStockReadings::create(['company_id' => $company_id, 'station_id' => $station_id,'tank_id' => $tank_id,'tank_code' => $tank_code, 'phy_shift_start_volume_reading' => $phy_shift_start_volume_reading, 'phy_shift_end_volume_reading' => $phy_shift_end_volume_reading,'created_by' => $created_by,'reading_date' => date_format(date_create($reading_date),"Y-m-d").' 00:00:00', 'status' =>$status, 'product'=> $product,'return_to_tank'=>$return_to_tank ,'git_loss' =>$git_loss,
                            'end_delivery'=>$end_delivery,'last_modified_by'=>$last_modified_by ]);
                  //       }
                   // if(count($present_2) == 0){
                        $sales = DailyTotalizerReadings::create(['company_id' => $company_id, 'station_id' => $station_id, 'nozzle_code' => $product, 'open_shift_totalizer_reading' => $opening_totalizer, 'close_shift_totalizer_reading' => $closing_totalizer,'created_by' => $created_by,'reading_date' => date_format(date_create($reading_date),"Y-m-d").' 00:00:00', 'status' =>$status, 'product'=> $product,'ppv'=>$ppv,
                            'last_modified_by'=>$last_modified_by ]);
                //    }

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
                    $stock = DailyStockReadings::where('reading_date', $data['reading_date'])->where('tank_id', $value['tank_id'])->update(['phy_shift_end_volume_reading' => $value['closing_reading'],'return_to_tank'=>$value['rtt'],'end_delivery'=>$value['qty_received'] ,'status' =>'Closed' ,'git_loss' =>$value['git_loss'] ]);
                    }else if($value['status'] == 'Modified'){
                    $stock = DailyStockReadings::where('reading_date', $value['reading_date'])->where('tank_id', $value['tank_id'])->update(['phy_shift_end_volume_reading' => $value['closing_reading'],'phy_shift_start_volume_reading' => $value['opening_reading'],'return_to_tank'=>$value['rtt'] ,'git_loss' =>$value['git_loss'],
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
    public function handle_file_upload($request)
    {   $this->current_user = JWTAuth::parseToken()->authenticate();
        $user_id = $this->current_user->id;
        $company_id = $this->current_user->company_id;

        if($request->hasFile('file')) {
            $fileItself = $request->file('file');
            $rows = array();
            $load = Excel::load($fileItself, function($reader) {})->get();
            $row = $load[0];
            if(!isset($row->station_code)){
                array_push($this->csv_error_log , ["message" => "Station Code column not specified"]);
            }else if(!isset($row->tank_code)){
                array_push($this->csv_error_log , ["message" => "Tank Code column not specified"]);
            }else if(!isset($row->date)){
                array_push($this->csv_error_log , ["message" => "Date column not specified"]);
            }else{
                //to verify if user has access to upload for that station
               $user_stations_details = $this->station_service->get_stations_by_user_id($user_id);
               foreach ($user_stations_details as $key => $value) {
                  array_push($this->user_station_ids, $value['station_id']);
               }
                foreach($load as $key => $row) {
                $this->validate_station_tank_code_and_upload_date($key, $row, $company_id);
                }
            }
        }
        return  array(['error' => $this->csv_error_log, 'success' => $this->csv_success_rows]);
    }

    // public function enyo_cash_xxx($request)
    // {   
    //     $this->current_user = JWTAuth::parseToken()->authenticate();
    //     $user_id = $this->current_user->id;
    //     $company_id = $this->current_user->company_id;
    //    //  //step 1
    //    // $station_ids = [38,178,24,29,32,86,5,91,84,30,89,87,27,34,25,1,21,26,181,182];
    //    //  foreach ($station_ids as $value) {
    //    //     Deposits::where('station_id', $value)->where('reading_date','>', '2017-12-31')->where('reading_date','<' ,'2018-09-06')->delete();
    //    //  }
       
    //     //step 2
    //      $entry = array();
    //     if($request->hasFile('file')) {
    //         $fileItself = $request->file('file');
    //         $deposits = array();
    //       $entry =  Excel::load($fileItself, function($reader){})->get();
          
    //       foreach ($entry as $key => $value) {
    //         foreach ($value as $key_2 => $value_2) {
            
    //         if(isset($value_2->station_id) and $value_2->station_id !=null and isset($value_2->date) and $value_2->date !=null  and is_object($value_2->date) and isset($value_2->cash_dep_acc) and $value_2->cash_dep_acc !=null ){
    //             $new = array('station_id' => $value_2->station_id , 'company_id'=> 8, 'teller_date' => $value_2->date->toDateTimeString(), 'reading_date' => $value_2->date->toDateTimeString(), 'amount' => $value_2->cash_dep_acc, 'upload_type' => 'Replace', 'payment_type' => 'Cash Deposit');

    //             $this->database->beginTransaction();
    //             try{
    //                 Deposits::create($new);        
    //             }catch (Exception $exception){
    //                 $this->database->rollBack();
    //                 throw $exception;
    //             }
    //             $this->database->commit();
    //             }
    //         }
         
    //         }
    // }

    //     return 1;
            
    // }


    public function bovas_handle_file_upload($request)
    {   
        $this->current_user = JWTAuth::parseToken()->authenticate();
        $user_id = $this->current_user->id;
        $company_id = $this->current_user->company_id;
        if($request->hasFile('file')) {
            $fileItself = $request->file('file');
            $rows = array();
            $load = Excel::load($fileItself, function($reader) {})->get();
            $row = $load[0];
            if(!isset($row->station_code)){
                array_push($this->csv_error_log , ["message" => "Station Code column not specified"]);
            }else if(!isset($row->product)){
                array_push($this->csv_error_log , ["message" => "Product column not specified"]);
            }else if(!isset($row->date)){
                array_push($this->csv_error_log , ["message" => "Date column not specified"]);
            }else if(!isset($row->ppv)){
                array_push($this->csv_error_log , ["message" => "PPV column not specified"]);
            }else{
                //to verify if user has access to upload for that station
               $user_stations_details = $this->station_service->get_stations_by_user_id($user_id);
               foreach ($user_stations_details as $key => $value) {
                  array_push($this->user_station_ids, $value['station_id']);
               }
                foreach($load as $key => $row) {
                $this->bovas_validate_station_tank_code_and_upload_date($key, $row, $company_id);
                }
            }
        }
        return  array(['error' => $this->csv_error_log, 'success' => $this->csv_success_rows]);
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
    private function validate_station_tank_code_and_upload_date($key, $row, $company_id){
     
        if($company_id != 'master'){
        $station_details  = Station::where('code', $row['station_code'])->where('company_id', $company_id)->get(['id', 'company_id', 'name'])->first();
            }
        else{
        $station_details  = Station::where('code', $row['station_code'])->get(['id', 'company_id', 'name'])->first();
        }
        $real_key = (int)$key+1;
        
        
        $row['station_id'] = $station_details['id'];
        $row['company_id'] = $station_details['company_id'];
        $row['station_name'] = $station_details['name'];

        if(count($station_details) == 0){
            array_push( $this->csv_error_log, ["message" => "Station with code ". $row['station_code']. " on row ".$real_key." not found, please confirm station code (check spelling)" ] );
        }else if($this->current_user->company_id != 'master' and !in_array($station_details['id'], $this->user_station_ids)){
            array_push($this->csv_error_log, ["message" => "You are not permitted to upload readings for ". $row['station_code']. " on row ".$real_key ]);
        }else{
            
            $tank_details  = Tanks::with('product:id,code')->where('code', $row['tank_code'])->where('station_id', $station_details['id'])->get(['id','product_id'])->first();

            if(count($tank_details) == 0){
                array_push($this->csv_error_log , ["message" => $row['tank_code']. " on row ".$real_key." not found for  station ".$row['station_code']. " (".$row['station_name']. ") please confirm tank code (check spelling)"]);
            }else{
                $row['tank_id'] = $tank_details['id'];
                $row['product'] = $tank_details->product['code'];
                $date = "%".date_format(date_create($row['date']),"Y-m-d")."%";
                $readings_details  = DailyStockReadings::where('tank_code', $row['tank_code'])->where('station_id', $station_details['id'])->where('reading_date','LIKE', $date)->get(['id'])->first();
                if(count($readings_details) > 0){
                    array_push($this->csv_error_log , ["message" => "Reading already exist for ". $row['tank_code']. " on row ".$real_key." please contact admin to modify, delete the row for now"]);
                }else{
                   array_push($this->csv_success_rows, $row);
                } 
            }
        }
        
    }
    private function bovas_validate_station_tank_code_and_upload_date($key, $row, $company_id){
        if($company_id != 'master'){
        $station_details  = Station::where('code', $row['station_code'])->where('company_id', $company_id)->get(['id', 'company_id', 'name'])->first();
            }
        else{
        $station_details  = Station::where('code', $row['station_code'])->get(['id', 'company_id', 'name'])->first();
        }
        $real_key = (int)$key+1;
        
        $row['station_id'] = $station_details['id'];
        $row['company_id'] = $station_details['company_id'];
        $row['station_name'] = $station_details['name'];

        if(count($station_details) == 0){
            array_push( $this->csv_error_log, ["message" => "Station with code ". $row['station_code']. " on row ".$real_key." not found, please confirm station code (check spelling)" ] );
        }else if($this->current_user->company_id != 'master' and !in_array($station_details['id'], $this->user_station_ids)){
            array_push($this->csv_error_log, ["message" => "You are not permitted to upload readings for ". $row['station_code']. " on row ".$real_key ]);
        }else{
            
               array_push($this->csv_success_rows, $row);
              
        }
        
    }

}
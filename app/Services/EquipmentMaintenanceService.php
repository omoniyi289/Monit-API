<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 2:17 AM
 */
namespace App\Services;
use App\Reposities\CompanyRepository;
use App\Reposities\EquipmentMaintenanceRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use App\Pumps;
use App\Models\PumpMaintenanceLog;
use App\Models\DailyTotalizerReadings;


class EquipmentMaintenanceService
{
    private $database;
    private $equipment_maintenance_repository;
    private $grouped_pump_array_for_maintenance_query;
    private $grouped_pump_array_for_pump_readings_query;

    public function __construct(DatabaseManager $database,EquipmentMaintenanceRepository $equipment_maintenance_repository)
    {
        $this->database = $database;
        $this->equipment_maintenance_repository = $equipment_maintenance_repository;
        $this->grouped_pump_array_for_maintenance_query = array();
        $this->grouped_pump_array_for_pump_readings_query = array();
    }
    // public function create(array $data) {
    //     $this->database->beginTransaction();
    //     try{
    //         $equipment_maintenance = $this->equipment_maintenance_repository->create($data);
    //     }catch (Exception $exception){
    //         $this->database->rollBack();
    //         throw $exception;
    //     }
    //     $this->database->commit();
    //     return EquipmentMaintenance::where('station_id',$data['station_id'])->with('product')->get();
    //     //return $equipment_maintenance;
    // }
    //  public function update($equipment_maintenance_id, array $data)
    // {
    //     $equipment_maintenance = $this->get_requested_equipment_maintenance($equipment_maintenance_id);
    //     $this->database->beginTransaction();
    //     try {
    //         $this->equipment_maintenance_repository->update($equipment_maintenance, $data);
    //     } catch (Exception $exception) {
    //         $this->database->rollBack();
    //         throw $exception;
    //     }
    //     $this->database->commit();
    //  return EquipmentMaintenance::where('station_id',$data['station_id'])->with('product')->get();
    // }
    // public function delete($equipment_maintenance_id, array $options = [])
    // {
    //     return  EquipmentMaintenance::where('id',$equipment_maintenance_id)->delete();
    // }

    public function get_pump_readings($options){
        $start_date = $options['start_date'];
        $end_date = $options['end_date'];
        $volume_category = 0;
        if( isset($options['volume_category']) and !empty($options['volume_category']) ){
         $volume_category = $options['volume_category'];
        }

        $station_ids = explode(",", $options['station_id']);
        $all_pumps = array();
        foreach ($station_ids as $key => $value) {
          $station_pumps = $this->equipment_maintenance_repository->get_station_pumps_readings($value, $start_date, $end_date);
          $this->combine_pump_totalizer_readings_for_pump_readings_log($station_pumps, $volume_category);
          //array_push($all_pumps,  $station_pumps);
        }
        return $this->grouped_pump_array_for_pump_readings_query;
    }

     public function get_pump_maintenance_and_current_readings($params)
    {   

    
    //if(isset($params['get_open_station_info'])){
          ////get pumps and their last inputs
        $station_ids = explode(",", $params['station_id']);
        foreach ($station_ids as $station_id) {
           
            $pumps = Pumps::where('station_id',$station_id)->with('product:id,code')->with('station:id,name,company_id')->orderBy('pump_nozzle_code', 'ASC')->get(['id', 'pump_nozzle_code', 'product_id', 'station_id']);

            foreach ($pumps as $key => $value) {
          
                $last_reading = DailyTotalizerReadings::select('id','close_shift_totalizer_reading', 'open_shift_totalizer_reading')->whereNotNull('close_shift_totalizer_reading')->where('pump_id',$value['id'])->orderBy('reading_date', 'desc')->get()->first();

                  $pumps[$key]['last_closing_reading'] = $last_reading['close_shift_totalizer_reading'];
                  $pumps[$key]['last_opening_reading'] = $last_reading['open_shift_totalizer_reading'];
                  $pumps[$key]['sales_since_last_maintenance']='';
                  $pumps[$key]['last_maintenance_date']='';
                  $pump_id = $value['id'];
                //  $last_maintenance_date = '';

                // $last_pump_maintenance = PumpMaintenanceLog::where('pump_id',$pump_id)->orderBy('maintenance_date', 'desc')->get(['id', 'maintenance_date'])->first();

                // if(count($last_pump_maintenance) > 0){
                //     $start_date = $last_pump_maintenance['maintenance_date'];
                //     $end_date = date('Y-m-d H:i:s');
                //      $pump_maintenance =  $this->equipment_maintenance_repository->get_pump_readings($pump_id, $start_date, $end_date);

                //     $pumps[$key]['last_maintenance_date']= $last_pump_maintenance['maintenance_date'];
                //     $pumps[$key]['sales_since_last_maintenance']= $pump_maintenance[0]->total_sales;
                    
                //     }
                // else{
                //    $pump_maintenance =  $this->equipment_maintenance_repository->get_pump_readings_from_inception($pump_id);  
                           
                //         if( count($pump_maintenance) > 0){
                //              $pumps[$key]['sales_since_last_maintenance']= $pump_maintenance[0]->total_sales;
                //              $pumps[$key]['last_maintenance_date']= $pump_maintenance[0]->min_date;   
                //          }
                //     }
                }

            $this->combine_pump_totalizer_readings_for_maintenance_query($pumps);
            
            }
            return $this->grouped_pump_array_for_maintenance_query;

   }

          public function get_pump_maintenance_log($params)
    {   

       $result = PumpMaintenanceLog::with('station:id,name,company_id')->where('station_id',$params['station_id']);
        $result->orderBy('id', 'desc');
        return $result->get();      
    }
       public function combine_pump_totalizer_readings_for_maintenance_query($pumps)
    {   
        $pumps = json_decode(json_encode($pumps), true); //from obj to array
        foreach ($pumps as $key => $value) {
               $combined_pump = array();
               $combined_pump['station_id']  = $value['station_id'];
               $combined_pump['station_name']  = $value['station']['name'];
               $combined_pump['product']  = $value['product']['code'];
               $combined_pump['past_log']['D_issue_date']  = '';
               $combined_pump['past_log']['MD_issue_date']  = '';
               $combined_pump['past_log']['MMD_issue_date']  = '';
               $combined_pump['past_log']['D_invoice_number']  = '';
               $combined_pump['past_log']['MD_invoice_number']  = '';
               $combined_pump['past_log']['MMD_invoice_number']  = '';
               $combined_pump['past_log']['D_maintenenance_date']  = '';
               $combined_pump['past_log']['MD_maintenenance_date']  = '';
               $combined_pump['past_log']['MMD_maintenenance_date']  = '';
               
               $pump_split_array =  explode(" ",  $value['pump_nozzle_code']);

               $actual_number = $pump_split_array[ count($pump_split_array) -1]; //e.g 02 or 2
               $number_as_int = intval($actual_number); //cast to int

               if( $number_as_int > 0  and $number_as_int%2 == 1 ){// if pump number is odd
                $odd_pump = $value;
                $even_pump_code = $pump_split_array[0]." ".$pump_split_array[1]." ".($number_as_int+1); //this is to guess the (even) code of other nozzle of the pump nozzle
                $key = array_search($even_pump_code, array_column($pumps, 'pump_nozzle_code')); //use the formed code to get the nozzle details in the station's pumps

                if( is_numeric($key) and $key > 0){
                  $even_pump =  $pumps[$key];
                  $combined_pump_number = ($number_as_int + 1)/2; //e.g (11+1)/2 = 6, joint code of pms pump 11 and pms pump 12 should be pms pump 6
                  $combined_pump_nozzle_code = $pump_split_array[0]." ".$pump_split_array[1]." ".$combined_pump_number;
                  $combined_pump['combined_pump_nozzle_code'] = $combined_pump_nozzle_code;
                  $combined_pump['combined_totalizer_reading'] = $even_pump['last_closing_reading'] + $odd_pump['last_closing_reading'];
                  $combined_pump['totalizer_1_reading'] = $odd_pump['last_closing_reading'];
                  $combined_pump['totalizer_2_reading'] = $even_pump['last_closing_reading'];
                  $combined_pump['pump_1_nozzle_code'] = $odd_pump['pump_nozzle_code'];
                  $combined_pump['pump_2_nozzle_code'] = $even_pump['pump_nozzle_code'];
                  //get past log
                  $past_log = PumpMaintenanceLog::where('combined_pump_nozzle_code', $combined_pump_nozzle_code)->where('station_id', $odd_pump['station_id'])->get()->first();
                  if(count($past_log) == 1){
                    $combined_pump['past_log'] = $past_log;
                    }
                  array_push($this->grouped_pump_array_for_maintenance_query, $combined_pump);
                }
                else{
                    //try by adding '0' to prefix the pump number and check if that exist
                    $even_pump_code = $pump_split_array[0]." ".$pump_split_array[1]." 0".($number_as_int+1);
                    $key = array_search($even_pump_code, array_column($pumps, 'pump_nozzle_code'));

                    if( is_numeric($key) and $key > 0){
                      $even_pump =  $pumps[$key];
                      $combined_pump_number = ($number_as_int + 1)/2;
                      $combined_pump_nozzle_code = $pump_split_array[0]." ".$pump_split_array[1]." ".$combined_pump_number;
                      $combined_pump['combined_pump_nozzle_code'] = $combined_pump_nozzle_code;
                      $combined_pump['combined_totalizer_reading'] = $even_pump['last_closing_reading'] + $odd_pump['last_closing_reading'];
                      $combined_pump['totalizer_1_reading'] = $odd_pump['last_closing_reading'];
                      $combined_pump['totalizer_2_reading'] = $even_pump['last_closing_reading'];
                      $combined_pump['pump_1_nozzle_code'] = $odd_pump['pump_nozzle_code'];
                      $combined_pump['pump_2_nozzle_code'] = $even_pump['pump_nozzle_code'];
                      //get past log
                      $past_log = PumpMaintenanceLog::where('combined_pump_nozzle_code', $combined_pump_nozzle_code)->where('station_id', $odd_pump['station_id'])->get()->first();
                      if(count($past_log) == 1){
                          $combined_pump['past_log'] = $past_log;
                        }
                      array_push($this->grouped_pump_array_for_maintenance_query, $combined_pump);
                    }
                    else{
                        //pump does not have a  pair, just the odd
                          $combined_pump_nozzle_code = $odd_pump['pump_nozzle_code'];
                          $combined_pump['combined_pump_nozzle_code'] = $combined_pump_nozzle_code;
                          $combined_pump['combined_totalizer_reading'] =  $odd_pump['last_closing_reading'];
                          $combined_pump['totalizer_1_reading'] = $odd_pump['last_closing_reading'];
                          $combined_pump['totalizer_2_reading'] = null;
                          $combined_pump['pump_1_nozzle_code'] = $odd_pump['pump_nozzle_code'];
                          $combined_pump['pump_2_nozzle_code'] = null;
                          //get past log
                          $past_log = PumpMaintenanceLog::where('combined_pump_nozzle_code', $combined_pump_nozzle_code)->where('station_id', $odd_pump['station_id'])->get()->first();
                          if(count($past_log) == 1){
                              $combined_pump['past_log'] = $past_log;
                            }
                          array_push($this->grouped_pump_array_for_maintenance_query, $combined_pump);
                    }


                }



               }
        }
       
    }
   
       public function combine_pump_totalizer_readings_for_pump_readings_log($pumps, $volume_category)
    {   
        $pumps = json_decode(json_encode($pumps), true); //from obj to array
        foreach ($pumps as $key => $value) {
               $combined_pump = array();
               $combined_pump['station_id']  = $value['station_id'];
               $combined_pump['station_name']  = $value['station_name'];
               $combined_pump['past_log']['D_issue_date']  = '';
               $combined_pump['past_log']['MD_issue_date']  = '';
               $combined_pump['past_log']['MMD_issue_date']  = '';
               $combined_pump['past_log']['D_invoice_number']  = '';
               $combined_pump['past_log']['MD_invoice_number']  = '';
               $combined_pump['past_log']['MMD_invoice_number']  = '';
               $combined_pump['past_log']['D_maintenenance_date']  = '';
               $combined_pump['past_log']['MD_maintenenance_date']  = '';
               $combined_pump['past_log']['MMD_maintenenance_date']  = '';
               
               $pump_split_array =  explode(" ",  $value['nozzle_code']);

               $actual_number = $pump_split_array[ count($pump_split_array) -1]; //e.g 02 or 2
               $number_as_int = intval($actual_number); //cast to int

               if( $number_as_int > 0  and $number_as_int%2 == 1 ){// if pump number is odd
                $odd_pump = $value;
                $even_pump_code = $pump_split_array[0]." ".$pump_split_array[1]." ".($number_as_int+1); //this is to guess the (even) code of other nozzle of the pump nozzle
                $key = array_search($even_pump_code, array_column($pumps, 'nozzle_code')); //use the formed code to get the nozzle details in the station's pumps

                if( is_numeric($key) and $key > 0){
                  $even_pump =  $pumps[$key];
                  $combined_pump_number = ($number_as_int + 1)/2; //e.g (11+1)/2 = 6, joint code of pms pump 11 and pms pump 12 should be pms pump 6
                  $combined_pump_nozzle_code = $pump_split_array[0]." ".$pump_split_array[1]." ".$combined_pump_number;
                  $combined_pump['combined_pump_nozzle_code'] = $combined_pump_nozzle_code;
                  $combined_pump['combined_min_reading'] = $even_pump['min_reading'] + $odd_pump['min_reading'];
                  $combined_pump['combined_max_reading'] = $even_pump['max_reading'] + $odd_pump['max_reading'];
                  
                  //get past log
                  $past_log = PumpMaintenanceLog::where('combined_pump_nozzle_code', $combined_pump_nozzle_code)->where('station_id', $odd_pump['station_id'])->get()->first();
                  if(count($past_log) == 1){
                    $combined_pump['past_log'] = $past_log;
                    }
                  if($combined_pump['combined_max_reading'] >= $volume_category){
                          array_push($this->grouped_pump_array_for_pump_readings_query, $combined_pump);
                        }
                }
                else{
                    //try by adding '0' to prefix the pump number and check if that exist
                    $even_pump_code = $pump_split_array[0]." ".$pump_split_array[1]." 0".($number_as_int+1);
                    $key = array_search($even_pump_code, array_column($pumps, 'nozzle_code'));

                    if( is_numeric($key) and $key > 0){
                      $even_pump =  $pumps[$key];
                      $combined_pump_number = ($number_as_int + 1)/2;
                      $combined_pump_nozzle_code = $pump_split_array[0]." ".$pump_split_array[1]." ".$combined_pump_number;
                      $combined_pump['combined_pump_nozzle_code'] = $combined_pump_nozzle_code;
                      $combined_pump['combined_min_reading'] = $even_pump['min_reading'] + $odd_pump['min_reading'];
                      $combined_pump['combined_max_reading'] = $even_pump['max_reading'] + $odd_pump['max_reading'];
                      //get past log
                      $past_log = PumpMaintenanceLog::where('combined_pump_nozzle_code', $combined_pump_nozzle_code)->where('station_id', $odd_pump['station_id'])->get()->first();
                      if(count($past_log) == 1){
                          $combined_pump['past_log'] = $past_log;
                        }
                      if($combined_pump['combined_max_reading'] >= $volume_category){
                          array_push($this->grouped_pump_array_for_pump_readings_query, $combined_pump);
                        }
                    }
                    else{
                        //pump does not have a  pair, just the odd
                          $combined_pump_nozzle_code = $odd_pump['nozzle_code'];
                          $combined_pump['combined_pump_nozzle_code'] = $combined_pump_nozzle_code;
                          $combined_pump['combined_min_reading'] = $odd_pump['min_reading'];
                          $combined_pump['combined_max_reading'] = $odd_pump['max_reading'];
                          //get past log
                          $past_log = PumpMaintenanceLog::where('combined_pump_nozzle_code', $combined_pump_nozzle_code)->where('station_id', $odd_pump['station_id'])->get()->first();
                          if(count($past_log) == 1){
                              $combined_pump['past_log'] = $past_log;
                            }
                          if($combined_pump['combined_max_reading'] >= $volume_category){
                          array_push($this->grouped_pump_array_for_pump_readings_query, $combined_pump);
                        }
                    }


                }



               }
        }
       
    }
   

    public function create_pump_maintenance_log(array $data) {
        $this->database->beginTransaction();
        $pump = '';
        try{
           if( count($data['readings'])  < 1 ){
                return 'invalid_input';
            }
            foreach ($data['readings'] as $value) {
                   if( true ){

                    $company_id = $data['company_id'];
                    $station_id = $value['station_id'];
                    $product = $value['product'];
                    $created_by = $data['created_by'];
                    $combined_totalizer_reading = $value['combined_totalizer_reading'];
                    $combined_pump_nozzle_code = $value['combined_pump_nozzle_code'];
                    $D_issue_date = $value['D_issue_date'] == null ? '' : date_format(date_create($value['D_issue_date']),"Y-m-d").' 00:00:00';      
                    $D_invoice_number = $value['D_invoice_number'];
                    $D_maintenenance_date = $value['D_maintenenance_date'];
                    $MD_issue_date = $value['MD_issue_date'] == null ? '' : date_format(date_create($value['MD_issue_date']),"Y-m-d").' 00:00:00';
                    $MD_invoice_number = $value['MD_invoice_number'];
                    $MD_maintenenance_date = $value['MD_maintenenance_date'];
                    $MMD_issue_date = $value['MMD_issue_date'] == null ? '' : date_format(date_create($value['MMD_issue_date']),"Y-m-d").' 00:00:00';
                    $MMD_invoice_number = $value['MMD_invoice_number'];
                    $MMD_maintenenance_date = $value['MMD_maintenenance_date'];
                    $note = $value['note'];

                    $pump_1_nozzle_code = $value['pump_1_nozzle_code'];
                    $pump_2_nozzle_code = $value['pump_2_nozzle_code'];
                    $totalizer_1_reading = $value['totalizer_1_reading'];
                    $totalizer_2_reading = $value['totalizer_2_reading'];

                    $pump_log = PumpMaintenanceLog::where('station_id', $station_id)->where('combined_pump_nozzle_code', $combined_pump_nozzle_code)->get()->first();

                    if(count($pump_log) ==1 ){
                        $pump =  PumpMaintenanceLog::where('id', $pump_log['id'])->update(['company_id' => $company_id, 'station_id' => $station_id,'combined_pump_nozzle_code' => $combined_pump_nozzle_code,'combined_totalizer_reading' => $combined_totalizer_reading, 'D_issue_date' => $D_issue_date, 'MD_issue_date' => $MD_issue_date,'MMD_issue_date' => $MMD_issue_date, 'D_invoice_number' => $D_invoice_number, 'MD_invoice_number' => $MD_invoice_number,'MMD_invoice_number' => $MMD_invoice_number,'totalizer_1_reading' =>$totalizer_1_reading, 'totalizer_2_reading' =>$totalizer_2_reading, 'pump_1_nozzle_code' =>$pump_1_nozzle_code, 'pump_2_nozzle_code' =>$pump_2_nozzle_code, 'product'=> $product,
                            'note'=>$note,'created_by'=>$created_by]);
                    }
                    else{
                        $pump =  PumpMaintenanceLog::create(['company_id' => $company_id, 'station_id' => $station_id,'combined_pump_nozzle_code' => $combined_pump_nozzle_code,'combined_totalizer_reading' => $combined_totalizer_reading, 'D_issue_date' => $D_issue_date, 'MD_issue_date' => $MD_issue_date,'MMD_issue_date' => $MMD_issue_date, 'D_invoice_number' => $D_invoice_number, 'MD_invoice_number' => $MD_invoice_number,'MMD_invoice_number' => $MMD_invoice_number,'totalizer_1_reading' =>$totalizer_1_reading, 'totalizer_2_reading' =>$totalizer_2_reading, 'pump_1_nozzle_code' =>$pump_1_nozzle_code, 'pump_2_nozzle_code' =>$pump_2_nozzle_code, 'product'=> $product,
                            'note'=>$note,'created_by'=>$created_by]);
                        }

                   //keep a log here as well for reference
                    //$pump =  DailyTotalizerReadings::create(['company_id' => $company_id, 'station_id' => $station_id, 'pump_id' => $pump_id,'nozzle_code' => $pump_nozzle_code, 'open_shift_totalizer_reading' => $new_totalizer_reading, 'close_shift_totalizer_reading' => $new_totalizer_reading,'created_by' => $created_by,'reading_date' => date_format(date_create($maintenance_date),"Y-m-d").' 00:00:00', 'status' =>'Closed', 'product'=> $product,'ppv'=>0,'cash_collected'=>0,'last_modified_by'=>$created_by , 'upload_type'=> 'Maintenance']);
                        }
                }
            
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $pump;
    }

    // public function get_by_id($user_id, array $options = [])
    // {
    //     return $this->get_requested_equipment_maintenance($user_id);
    // }
    // public function get_equipment_maintenance_by_code($name, $station_id)
    // {
    //     return EquipmentMaintenance::where("equipment_maintenance_nozzle_code", $name)->where('station_id',$station_id)->with('product')->get();
    // }
    //   public function get_by_station_id($station_id)
    // {
    //    return EquipmentMaintenance::where('station_id',$station_id)->with('product')->orderBy('equipment_maintenance_nozzle_code', 'ASC')->get();
    // }
    // private function get_requested_equipment_maintenance($user_id, array $options = [])
    // {
    //     return $this->equipment_maintenance_repository->get_by_id($user_id, $options);
    // }
}
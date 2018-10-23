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

    public function __construct(DatabaseManager $database,EquipmentMaintenanceRepository $equipment_maintenance_repository)
    {
        $this->database = $database;
        $this->equipment_maintenance_repository = $equipment_maintenance_repository;
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
        $station_id = $options['station_id'];
        $start_date = $options['start_date'];
        $end_date = $options['end_date'];
        
        return $this->equipment_maintenance_repository->get_station_pumps_readings($station_id, $start_date, $end_date);
    }

     public function get_pump_maintenance_and_current_readings($params)
    {   

    
    //if(isset($params['get_open_station_info'])){
          ////get pumps and their last inputs
            $pumps = Pumps::where('station_id',$params['station_id'])->with('product')->with('station:id,name,company_id')->orderBy('pump_nozzle_code', 'ASC')->get(['id', 'pump_nozzle_code', 'product_id']);
            foreach ($pumps as $key => $value) {
          
                $last_reading = DailyTotalizerReadings::select('id','close_shift_totalizer_reading', 'open_shift_totalizer_reading')->where('pump_id',$value['id'])->orderBy('reading_date', 'desc')->get()->first();

                  $pumps[$key]['last_closing_reading'] = $last_reading['close_shift_totalizer_reading'];
                  $pumps[$key]['last_opening_reading'] = $last_reading['open_shift_totalizer_reading'];
                  $pumps[$key]['sales_since_last_maintenance']='';
                  $pumps[$key]['last_maintenance_date']='';
                  $pump_id = $value['id'];
                  $last_maintenance_date = '';

                $last_pump_maintenance = PumpMaintenanceLog::where('pump_id',$pump_id)->orderBy('maintenance_date', 'desc')->get(['id', 'maintenance_date'])->first();

                if(count($last_pump_maintenance) > 0){
                    $start_date = $last_pump_maintenance['maintenance_date'];
                    $end_date = date('Y-m-d H:i:s');
                     $pump_maintenance =  $this->equipment_maintenance_repository->get_pump_readings($pump_id, $start_date, $end_date);

                    $pumps[$key]['last_maintenance_date']= $last_pump_maintenance['maintenance_date'];
                    $pumps[$key]['sales_since_last_maintenance']= $pump_maintenance[0]->total_sales;
                    
                    }
                else{
                   $pump_maintenance =  $this->equipment_maintenance_repository->get_pump_readings_from_inception($pump_id);  
                           
                        if( count($pump_maintenance) > 0){
                             $pumps[$key]['sales_since_last_maintenance']= $pump_maintenance[0]->total_sales;
                             $pumps[$key]['last_maintenance_date']= $pump_maintenance[0]->min_date;   
                         }
                    }
                }
           return $pumps;
       
   }

          public function get_pump_maintenance_log($params)
    {   

       $result = PumpMaintenanceLog::with('station:id,name,company_id')->where('station_id',$params['station_id']);
        $result->orderBy('id', 'desc');
        return $result->get();      
    }

    public function create_pump_maintenance_log(array $data) {
        $this->database->beginTransaction();
        $pump = '';
        try{
           if( count($data['readings'])  < 1 ){
                return 'invalid_input';
            }
            foreach ($data['readings'] as $value) {
                   if( !empty($value['new_totalizer_reading']) and !empty($value['maintenance_date']) ){
                    $company_id = $data['company_id'];
                    $station_id = $data['station_id'];
                    $pump_nozzle_code = $value['pump_nozzle_code'];
                    $maintenance_date = $value['maintenance_date'];                    
                    $created_by = $data['created_by'];
                    $new_totalizer_reading = $value['new_totalizer_reading'];
                    $current_totalizer_reading = $value['current_totalizer_reading'];
                    $product = $value['product'];
                    $pump_id = $value['pump_id'];
                    $note = $value['note'];

                 
                    $pump =  PumpMaintenanceLog::create(['company_id' => $company_id, 'station_id' => $station_id, 'pump_id' => $pump_id,'nozzle_code' => $pump_nozzle_code, 'totalizer_before_maintenance' => $current_totalizer_reading, 'totalizer_after_maintenance' => $new_totalizer_reading,'created_by' => $created_by,'maintenance_date' => date_format(date_create($maintenance_date),"Y-m-d").' 00:00:00', 'status' =>'Closed', 'product'=> $product,'ppv'=>0,
                            'note'=>$note,'last_modified_by'=>$created_by]);

                   //keep a log here as well for reference
                    $pump =  DailyTotalizerReadings::create(['company_id' => $company_id, 'station_id' => $station_id, 'pump_id' => $pump_id,'nozzle_code' => $pump_nozzle_code, 'open_shift_totalizer_reading' => $new_totalizer_reading, 'close_shift_totalizer_reading' => $new_totalizer_reading,'created_by' => $created_by,'reading_date' => date_format(date_create($maintenance_date),"Y-m-d").' 00:00:00', 'status' =>'Closed', 'product'=> $product,'ppv'=>0,
                            'cash_collected'=>0,'last_modified_by'=>$created_by , 'upload_type'=> 'Maintenance']);
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
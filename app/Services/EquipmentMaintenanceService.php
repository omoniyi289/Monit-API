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
        
        return $this->equipment_maintenance_repository->get_pump_readings($station_id, $start_date, $end_date);
    }

          public function get_pump_maintenance_log($params)
    {   

       $result = PumpMaintenanceLog::where('station_id',$params['station_id']);
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
<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 2:17 AM
 */

namespace App\Services;


use App\Reposities\CompanyRepository;
use App\Reposities\PumpGroupsRepository;
use App\Reposities\PumpsRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use App\Models\CompanyNotifications;
class CompanyNotificationsService
{
    private $database;
    private $pump_repository;

    public function __construct(DatabaseManager $database,PumpsRepository $pump_repository)
    {
        $this->database = $database;
        $this->pump_repository = $pump_repository;
    }
    public function create(array $data) {
        $this->database->beginTransaction();
        try{
            //return $data;
            foreach ($data as $value){
            $notification_exist =  CompanyNotifications::where('company_id', $value['company_id'])->where('notification_UI_slug', $value['notification_UI_slug'])->get()->first();
            //if notifications settings already exist
            if(count($notification_exist) > 0){
                CompanyNotifications::where('company_id', $value['company_id'])->where('notification_UI_slug', $value['notification_UI_slug'])->update($value);
            }else{
                CompanyNotifications::create($value);
                }
            }
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        ///return company's current settings
        return CompanyNotifications::where('company_id', $data[0]['company_id'])->get();
        //return $pumps;
    }
     public function update($pump_id, array $data)
    {
        $pump = $this->get_requested_pump($pump_id);
        $this->database->beginTransaction();
        try {
            $this->pump_repository->update($pump, $data);
        } catch (Exception $exception) {
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
     return CompanyNotifications::where('station_id',$data['station_id'])->with('product')->get();
    }
    public function delete($pump_id, array $options = [])
    {
        return  CompanyNotifications::where('id',$pump_id)->delete();
    }

  
    public function get_by_id($user_id, array $options = [])
    {
        return $this->get_requested_pump($user_id);
    }
    public function get_by_company_id($company_id)
    {
        return CompanyNotifications::where("company_id", $company_id)->get();
    }
      public function get_by_station_id($station_id)
    {
       return CompanyNotifications::where('station_id',$station_id)->with('product')->orderBy('pump_nozzle_code', 'ASC')->get();
    }
   
}
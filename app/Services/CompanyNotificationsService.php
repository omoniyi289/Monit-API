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
use App\Models\CompanyNotification;
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
            $notification_exist =  CompanyNotification::where('company_id', $value['company_id'])->where('notification_UI_slug', $value['notification_UI_slug'])->get()->first();
            //if notifications settings already exist
            if(count($notification_exist) > 0){
                CompanyNotification::where('company_id', $value['company_id'])->where('notification_UI_slug', $value['notification_UI_slug'])->update($value);
            }
    // //will always exist for the concerned company since app is now modular
            // else{
            //     CompanyNotification::create($value);
            //     }
            }
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        ///return company's current settings
        return CompanyNotification::where('company_id', $data[0]['company_id'])->get();
        //return $pumps;
    }

    public function delete($notf_id, array $options = [])
    {
        return  CompanyNotification::where('id',$notf_id)->delete();
    }

       public function get_by_params($request)
    {
        $result = CompanyNotification::with('notification:id,name');
        if(isset($request['company_id'])){
            $company_id = $request['company_id'];
            $result = $result->where('company_id', $company_id);
        }  
        return $result->get();
    }
   
   
}
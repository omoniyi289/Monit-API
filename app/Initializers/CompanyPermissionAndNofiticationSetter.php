<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 2:17 AM
 */

namespace App\Initializers;
use App\Reposities\CompanyRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;
use App\Company;
use App\Permission;
use App\Models\StationUsers;
use App\Models\NotificationModules;
use App\Models\CompanyNotification;
use App\Models\CompanyPermission;

ini_set('max_execution_time', 80000); 
class CompanyPermissionAndNofiticationSetter
{

    public function __construct(DatabaseManager $database,Dispatcher $dispatcher)
    {
        $this->database = $database;
        $this->dispatcher = $dispatcher;
    }

     public function pm_setter(){
        $this->database->beginTransaction();
        $arr=array();
        $counter = 0;
        try{
          $company = Company::get(['id', 'name']);  
          $notifications = NotificationModules::get(['id', 'UI_slug', 'name']); 
          $permissions = Permission::get(['id', 'UI_slug', 'name']);

          foreach ($company as $key => $value) {

            foreach ($notifications as $value2) {
              CompanyNotification::create(['company_id' => $value['id'], 'notification_id' => $value2['id'], 'notification_UI_slug' => $value2['UI_slug'], 'notification_name' => $value2['name']  ]);
           }

           foreach ($permissions as $value3) {
              CompanyPermission::create(['company_id' => $value['id'], 'permission_id' => $value3['id'] ]);
           }   

          }
            
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $counter;
    }
   
 
       
}
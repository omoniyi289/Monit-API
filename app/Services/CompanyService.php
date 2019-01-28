<?php

namespace App\Services;
use App\Reposities\CompanyRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;
use App\Company;
use App\RolePermission;
use App\Models\CompanyPermission;
use App\Models\CompanyNotification;
use App\Models\UserNotifications;
use App\User;


class CompanyService
{
    private $database;
    private $dispatcher;
    private $company_repository;

    public function __construct(DatabaseManager $database,Dispatcher $dispatcher,CompanyRepository $company_repository)
    {
        $this->database = $database;
        $this->dispatcher = $dispatcher;
        $this->company_repository = $company_repository;
    }

  public function create(array $data){
        $this->database->beginTransaction();
        //return $data;
        try{
            
            $company = $this->company_repository->create($data);
            foreach ($data['selected_privileges'] as $value) {
                    //$permission= Permission::where('id', $value)->get()->first();
                    CompanyPermission::create([ 'permission_id' => $value['id'], 'company_id' => $company['id']]);
                }
                foreach ($data['selected_notifications'] as $value) {
                    CompanyNotification::create([ 'notification_id' => $value['id'] , 'notification_UI_slug' => $value['UI_slug'] , 'notification_name' => $value['name'], 'company_id' => $company['id']]);
                }
            
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return Company::where('id', $company['id'])->with('company_notifications.notification')->with('company_permissions.permission')->get()->first();
    }
     public function update($company_id, array $data)
    {
        $company = $this->get_requested_company($company_id);
        $this->database->beginTransaction();
        try {
            $this->company_repository->update($company, $data);

             $new_permission_ids = array();
             $new_notification_ids = array();

            //get current company notifications and permissions
                $current_permissions = CompanyPermission::where('company_id', $data['id'])->get(['permission_id']);

                $current_notifications = CompanyNotification::where('company_id', $data['id'])->get();
                $current_notifications_array =  array();
                foreach ($current_notifications as $key => $value) {
                    array_push($current_notifications_array, $value);
                 }
            //delete and re-update with new notifications and permissions for the company
              if(isset($data['selected_privileges'])){
                 CompanyPermission::where('company_id', $data['id'])->delete();
                 foreach ($data['selected_privileges'] as $value) {
                    CompanyPermission::create([ 'permission_id' => $value['id'], 'company_id' => $data['id']]);
                    array_push($new_permission_ids, $value['id']);
                    }
                }

             if(isset($data['selected_notifications'])){
                 CompanyNotification::where('company_id', $data['id'])->delete();
                 foreach ($data['selected_notifications'] as $value) {
                    //if company alreadt set notifcation weekday and daytime, keep the settings
                    $notification_daytime= null;
                    $notification_weekday= null;
                    $key = array_search($value['id'], array_column($current_notifications_array, 'notification_id'));
                    
                    if(isset($current_notifications_array[$key])){
                         $notification_weekday= $current_notifications_array[$key]['notification_weekday'];
                         $notification_daytime= $current_notifications_array[$key]['notification_daytime'];
                        }
                  
                   
                    CompanyNotification::create(['notification_id' => $value['id'],'notification_name' => $value['name'],'notification_UI_slug' => $value['UI_slug'],  'company_id' => $data['id'],'notification_weekday' => $notification_weekday,  'notification_daytime' => $notification_daytime ]);
                    array_push($new_notification_ids, $value['id']);
                    }
                }

            foreach ($current_permissions as $key => $value) {
              if(!in_array($value['permission_id'], $new_permission_ids)){
                    //permission no longer exist for the company so delete or all their user roles with it
                    RolePermission::where('permission_id', $value['permission_id'])->where('company_id', $data['id'])->delete();
              }
            }

            foreach ($current_notifications as $key => $value) {
              if(!in_array($value['notification_id'], $new_notification_ids)){
                    //notifications no longer exist for the company so delete or all their users with it
                    UserNotifications::where('notification_id', $value['notification_id'])->where('company_id', $data['id'])->delete();
              }
            }

        } catch (Exception $exception) {
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return Company::with('company_permissions.permission')->with('company_notifications.notification')->get();
    }
    public function get_company_by_name($name){
        return $this->company_repository->get_where('name',$name);
    }

    public function get_company_by_user_id($user_id){
        return $this->company_repository->get_where('user_id',$user_id);
    }

    public function get_all(array $options = []){
        return Company::with('company_permissions.permission:id,name')->with('company_notifications.notification')->get();
    }
     public function get_active(array $options = []){
        return Company::with('company_permissions.permission:id,name')->with('company_notifications.notification:id,name')->where('active', 1)->get();
    }
    public function get_by_id($user_id, array $options = [])
    {
        ///leave it at get, else trouble in frontend
        return Company::with('company_permissions.permission')->with('company_notifications.notification')->where('id', $user_id)->get();
    }
    public function get_for_prime_user($user_id, array $options = [])
    {
        ///leave it at get, else trouble in frontend
        
        return Company::where('user_id', $user_id)->get();
    }
    public function delete($company_id, array $options = [])
    {
        User::where('company_id', $company_id)->delete();
        return  Company::where('id',$company_id)->delete();
    }
    public function get_company_by_reg_no($reg_no)
    {
        return $this->company_repository->get_where('registration_number',$reg_no);
    }
    private function get_requested_company($user_id, array $options = [])
    {
        return $this->company_repository->get_by_id($user_id, $options);
    }
}
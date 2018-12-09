<?php
/**
 * Created by PhpStorm.
 * User: omoniyiomotoso
 * Date: 1/14/18
 * Time: 2:17 AM
 */

namespace App\Services;

use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use App\User;
use App\RolePermission;
use App\Permission;
use App\Models\VeloxCustomerVendor;
use App\Models\VeloxCustomerWallet;
use App\Models\VeloxVendor;
use App\Models\VeloxPaymentHistory;
use App\Events\VeloxPaymentRequestGenerated;
use App\Reposities\VeloxPaymentRepository;
 
class VeloxPaymentService
{
    private $database;
    private $vp_repository;

    public function __construct(DatabaseManager $database, VeloxPaymentRepository $vp_repository)
    {
        $this->database = $database;
        $this->vp_repository = $vp_repository;
    }
   public function create(array $data){
  
    try{
            $result = $this->vp_repository->create($data);
           if( isset($result) and count($result) > 0 ){
            $permission  = Permission::where('UI_slug', 'EVCMPC50')->get(['id'])->first();
            $role_permission = RolePermission::where('company_id', $data['customer_payment']['company_id'] )->where('permission_id', $permission['id'])->get(['id', 'role_id', 'company_id']);
        
            foreach ($role_permission as $key => $value) {
                $users = User::where('role_id', $value['role_id'])->get(['id', 'email', 'fullname']);  
                foreach ($users as $key => $user) {
                   $mail_data = ['user'=>$user, 'data' => $data['customer_payment']
                 ];
                    event(new VeloxPaymentRequestGenerated($mail_data));                        
                }             
            }    
           }
        }
    catch (Exception $exception){
        $this->database->rollBack();
        throw $exception;
        }
        return $result->data; 
    }
     public function update(array $data){
  
    try{
        $result = $this->vp_repository->update($data);    
        }
    catch (Exception $exception){
        $this->database->rollBack();
        throw $exception;
        }
        return $result->data; 
    }
    

     public function get_by_params($request)
    {
             $result = $this->vp_repository->get_by_params($request);  
             return $result->data;  
    }
  
   
}
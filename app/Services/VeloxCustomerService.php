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
use App\Models\VeloxCustomerVendor;
use App\Models\VeloxCustomerWallet;
use App\Models\VeloxVendor;
use App\Reposities\VeloxCustomerRepository;
 
class VeloxCustomerService
{
    private $database;
    private $vc_repository;

    public function __construct(DatabaseManager $database, VeloxCustomerRepository $velox_customer_repository)
    {
        $this->database = $database;
        $this->vc_repository = $velox_customer_repository;
    }
   public function create(array $data){
    $this->database->beginTransaction();
    try{
                 //initial company wallet
         VeloxCustomerVendor::create(['company_id' => $data['company_id'], 'vendor_id' => $data['vendor_id']]);
            
        }
    catch (Exception $exception){
        $this->database->rollBack();
        throw $exception;
        }

        $this->database->commit();
        return $company_vendor;
    }
    
    public function update($request)
    {
        //return  $request['wallet']['current_credit_limit'];
        $result =  VeloxCustomerVendor::where('id', $request['customer_vendor_id'])->update(['status' => $request['status'] ]);
        $result_2 =  VeloxCustomerWallet::where('company_id', $request['company_id'])->where('vendor_id', $request['vendor_id'])->update(['current_credit_limit' => $request['current_credit_limit'] ]);

          return $this->vc_repository->get_by_vendor_id($request['vendor_id']);

    }
 
     public function get_company_id_on_velox($company_id)
    {
        return VeloxVendor::where('sm_company_id', $company_id)->get()->first();

    }

    public function get_by_params($request)
    {

        if(isset($request['status'])){
            $company_id = $request['vendor_id'];
            $velox_company_detials = $this->get_company_id_on_velox($company_id);
            //$result = $result->where('vendor_id', $velox_company_detials['id']);
            return $result =  $this->vc_repository->get_by_status($velox_company_detials['id'], $request['status']);
        }else if(isset($request['vendor_id']) and !isset($request['status']) ){
            $company_id = $request['vendor_id'];
            $velox_company_detials = $this->get_company_id_on_velox($company_id);
            //$result = $result->where('vendor_id', $velox_company_detials['id']);
            return $result =  $this->vc_repository->get_by_vendor_id($velox_company_detials['id']);
        } 
    }
  
   
}
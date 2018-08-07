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
use App\Models\VeloxCustomerVendor;
use App\Models\VeloxCustomerWallet;
use App\Models\VeloxVendor;
use App\Models\VeloxFuelPurchaseHistory;
use App\Reposities\VeloxPurchaseRepository;
 
class VeloxPurchaseService
{
    private $database;
    private $vp_repository;

    public function __construct(DatabaseManager $database, VeloxPurchaseRepository $vp_repository)
    {
        $this->database = $database;
        $this->vp_repository = $vp_repository;
    }
   
    
     public function get_company_id_on_velox($company_id)
    {
        return VeloxVendor::where('sm_company_id', $company_id)->get()->first();

    }
   
     public function get_by_params($request)
    {
        $result = VeloxFuelPurchaseHistory::where('id', '>', 0);
        if(isset($request['customer_id'])){
            $customer_id = $request['customer_id'];
            $result = $result->where('company_id', $customer_id);

        }  

        if(isset($request['vendor_id'])){
            $company_id = $request['vendor_id'];
            $velox_company_detials = $this->get_company_id_on_velox($company_id);
            $result = $result->where('vendor_id', $velox_company_detials['id']);

        }          
        return $result->get();
    }
  
   
}
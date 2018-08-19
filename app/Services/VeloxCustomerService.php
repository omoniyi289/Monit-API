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
   public function update($request){
    try{
         $result =  $this->vc_repository->update($request);
        }
    catch (Exception $exception){
        $this->database->rollBack();
        throw $exception;
        }
        return $result->data;  ;
    }
    
 

    public function get_by_params($request)
    {
        $result =  $this->vc_repository->get_by_params($request);
        return $result->data;  
    }
  
   
}
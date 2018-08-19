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
   
      public function get_by_params($request)
    {
             $result = $this->vp_repository->get_by_params($request);  
             return $result->data;  
    }
   
}
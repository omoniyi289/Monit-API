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
use App\Pumps;
class VeloxPumpService
{
    private $database;
    private $pump_repository;

    public function __construct(DatabaseManager $database,PumpsRepository $pump_repository)
    {
        $this->database = $database;
        $this->pump_repository = $pump_repository;
    }
   public function get_by_params($request)
    {
        $result = Pumps::where('id', '>', 0);
        if(isset($request['station_id'])){
            $station_id = $request['station_id'];
            $result = $result->where('station_id', $station_id);

        }  

        if(isset($request['product'])){
            $product = $request['product'];
            $result = $result->where('pump_nozzle_code', 'LIKE', $product.'%');
        } 
         
        if(isset($request['serial_number'])){
            $serial_number = $request['serial_number'];
            $result = $result->where('serial_number', $serial_number);
        }          
        return $result->get(['pump_nozzle_code']);
    }

}
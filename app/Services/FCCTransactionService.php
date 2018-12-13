<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 2:17 AM
 */

namespace App\Services;


use App\Reposities\CompanyRepository;
use App\Models\FCCTransaction;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;

class FCCTransactionService
{
    private $database;
    private $dispatcher;
   
    public function __construct(DatabaseManager $database,Dispatcher $dispatcher)
    {
        $this->database = $database;
        $this->dispatcher = $dispatcher;
    
    }
    // public function create(array $data){
    //     $this->database->beginTransaction();
    //     try{
    //         $permissions = $this->permission_repository->create($data);
    //     }catch (Exception $exception){
    //         $this->database->rollBack();
    //         throw $exception;
    //     }
    //     $this->database->commit();
    //     return $permissions;
    // }


    // public function get_all(array $options = []){
    //     return $this->permission_repository->get($options);
    // }
    // public function get_by_id($permission_id, array $options = [])
    // {
    //     return $this->get_requested_permission($permission_id);
    //}
    public function get_requested_transaction_by_seq_no($params)
    {
        $oem_station_id = $params['oem_station_id'];
        $master_seq_no = $params['master_seq_no'];
        $pump_id = $params['pump_id'];

        return FCCTransaction::where('masterseq', $master_seq_no)->where('pumpid', $pump_id)->where('stationid', $oem_station_id)->get()->first();
    }
}
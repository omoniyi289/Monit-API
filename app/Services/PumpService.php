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
class PumpService
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
            $pumps = $this->pump_repository->create($data);
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $pumps;
    }
     public function update($pump_id, array $data)
    {
        $pump = $this->get_requested_pump($pump_id);
        $this->database->beginTransaction();
        try {
            $this->pump_repository->update($pump, $data);
        } catch (Exception $exception) {
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $pump;
    }

    public function get_all(array $options = []){
        return $this->pump_repository->get($options);
    }
    public function get_by_id($user_id, array $options = [])
    {
        return $this->get_requested_pump($user_id);
    }
      public function get_by_station_id($station_id)
    {
       return Pumps::where('station_id',$station_id)->where('pump_group_id', null)->with('product')->get();
    }
    private function get_requested_pump($user_id, array $options = [])
    {
        return $this->pump_repository->get_by_id($user_id, $options);
    }
}
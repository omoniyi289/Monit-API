<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/15/18
 * Time: 10:57 AM
 */

namespace App\Services;


use App\Reposities\StationRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use App\Station;
use App\Models\StationUsers;
class StationService
{
    private $database;
    private $station_repository;
    private $dispatcher;

    public function __construct(DatabaseManager $database,StationRepository $station_repository,
                                Dispatcher $dispatcher)
    {
        $this->database = $database;
        $this->dispatcher = $dispatcher;
        $this->station_repository = $station_repository;
    }

    public function create($data){
        $this->database->beginTransaction();
        try{
            $station = $this->station_repository->create($data);
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $station;
    }

    public function update($station_id, array $data)
    {
        $station = $this->get_requested_station($station_id);
        $this->database->beginTransaction();
        try {
            $this->station_repository->update($station, $data);
        } catch (Exception $exception) {
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $station;
    }
    public function delete($station_id, array $options = [])
    {
        StationUsers::where('station_id',$station_id)->delete();
         
        return Station::where('id',$station_id)->delete();
        
    }

    public function get_all(array $options = []){
        return $this->station_repository->get($options);
    }

    public function get_station_by_company_id($company_id){
        return Station::where("company_id",$company_id)->orderBy('name', 'desc')->get();
    }
    public function get_station_by_state($state){
        return $this->station_repository->get_where("state",$state);
    }
    public function get_stations_by_user_id($user_id){
        //return $this->station_repository->get_where("company_id",$company_id);
        return  StationUsers::where('company_user_id',$user_id)->with('station')->get();
    }
    public function get_station_by_name($name)
    {
        return $this->station_repository->get_where("name", $name);
    }
     public function get_station_by_code($code)
    {
        return $this->station_repository->get_where("code", $code);
    }
    public function get_by_id($station_id, array $options = [])
    {
        return $this->get_requested_station($station_id);
    }
   
    private function get_requested_station($station_id, array $options = [])
    {
        return $this->station_repository->get_by_id($station_id, $options);
    }
}
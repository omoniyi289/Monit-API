<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 2:17 AM
 */

namespace App\Services;


use App\Reposities\CompanyRepository;
use App\Reposities\TankGroupRepository;
use App\Reposities\TanksRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use App\Tanks;

class TankService
{
    private $database;
    private $tank_repository;

    public function __construct(DatabaseManager $database, TanksRepository $tank_repository)
    {
        $this->database = $database;
        $this->tank_repository = $tank_repository;
    }

    public function create(array $data)
    {
        $this->database->beginTransaction();
        try {
            $tanks = $this->tank_repository->create($data);
        } catch (Exception $exception) {
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $tanks;
    }


    public function update($tank_id, array $data)
    {
        $tank = $this->get_requested_tank($tank_id);
        $this->database->beginTransaction();
        try {
            $this->tank_repository->update($tank, $data);
        } catch (Exception $exception) {
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $tank;
    }

    public function get_all(array $options = [])
    {
        return $this->tank_repository->get($options);
    }
     public function get_by_station_id($station_id)
    {
       return Tanks::where('station_id',$station_id)->with('product')->get();
    }

     public function get_by_id($station_id, array $options = [])
    {
        return Tanks::where('station_id',$station_id)->with('product')->get();
    }

    private function get_requested_tank($station_id, array $options = [])
    {
        return $this->tank_repository->get_by_id($station_id, $options);
    }
}
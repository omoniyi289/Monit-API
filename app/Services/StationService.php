<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/15/18
 * Time: 10:57 AM
 */

namespace App\Services;


use App\Resposities\StationRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;

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

    public function get_all(array $options = []){
        return $this->station_repository->get($options);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 2:17 AM
 */

namespace App\Services;


use App\Resposities\CompanyRepository;
use App\Resposities\PumpGroupsRepository;
use App\Resposities\PumpsRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;

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

    public function get_all(array $options = []){
        return $this->pump_repository->get($options);
    }
    public function get_by_id($user_id, array $options = [])
    {
        return $this->get_requested_pump($user_id);
    }
    private function get_requested_pump($user_id, array $options = [])
    {
        return $this->pump_repository->get_by_id($user_id, $options);
    }
}
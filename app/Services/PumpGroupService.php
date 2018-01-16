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
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;

class PumpGroupService
{
    private $database;
    private $pump_groups_repository;

    public function __construct(DatabaseManager $database,PumpGroupsRepository $pump_groups_repository)
    {
        $this->database = $database;
        $this->pump_groups_repository = $pump_groups_repository;
    }

    public function create(array $data) {
        $this->database->beginTransaction();
        try{
            $company = $this->pump_groups_repository->create($data);
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $company;
    }

    public function get_all(array $options = []){
        return $this->pump_groups_repository->get($options);
    }
    public function get_by_id($user_id, array $options = [])
    {
        return $this->get_requested_pump_group($user_id);
    }
    private function get_requested_pump_group($user_id, array $options = [])
    {
        return $this->pump_groups_repository->get_by_id($user_id, $options);
    }
}
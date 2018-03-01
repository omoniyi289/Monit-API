<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 2:17 AM
 */

namespace App\Services;


use App\Reposities\CompanyRepository;
use App\Reposities\PumpGroupToTankGroupRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;

class PumpGroupToTankGroupService
{
    private $database;
    private $dispatcher;
    private $pump_group_to_tank_group_repository;

    public function __construct(DatabaseManager $database, Dispatcher $dispatcher, PumpGroupToTankGroupRepository $pump_group_to_tank_group_repository)
    {
        $this->database = $database;
        $this->dispatcher = $dispatcher;
        $this->pump_group_to_tank_group_repository = $pump_group_to_tank_group_repository;
    }

    public function create(array $data)
    {
        $this->database->beginTransaction();
        try {
            $pump_group_to_tank_group = $this->pump_group_to_tank_group_repository->create($data);
        } catch (Exception $exception) {
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $pump_group_to_tank_group;
    }
    public function get_all(array $options = [])
    {
        return $this->pump_group_to_tank_group_repository->get($options);
    }

    public function get_by_id($user_id, array $options = [])
    {
        return $this->get_requested_pump_group_to_tank_group($user_id);
    }

    private function get_requested_pump_group_to_tank_group($user_id, array $options = [])
    {
        return $this->pump_group_to_tank_group_repository->get_by_id($user_id, $options);
    }
}
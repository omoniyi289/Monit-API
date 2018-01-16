<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 2:17 AM
 */

namespace App\Services;


use App\Resposities\CompanyRepository;
use App\Resposities\TankGroupRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;

class TankGroupService
{
    private $database;
    private $dispatcher;
    private $tank_group_repository;

    public function __construct(DatabaseManager $database, Dispatcher $dispatcher, TankGroupRepository $tank_group_repository)
    {
        $this->database = $database;
        $this->dispatcher = $dispatcher;
        $this->tank_group_repository = $tank_group_repository;
    }

    public function create(array $data)
    {
        $this->database->beginTransaction();
        try {
            $tank_group = $this->tank_group_repository->create($data);
        } catch (Exception $exception) {
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $tank_group;
    }
    public function get_all(array $options = [])
    {
        return $this->tank_group_repository->get($options);
    }

    public function get_by_id($user_id, array $options = [])
    {
        return $this->get_requested_tank_group($user_id);
    }

    private function get_requested_tank_group($user_id, array $options = [])
    {
        return $this->tank_group_repository->get_by_id($user_id, $options);
    }
}
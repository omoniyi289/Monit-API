<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 2:17 AM
 */

namespace App\Services;


use App\Reposities\CompanyRepository;
use App\Reposities\PermissionRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;

class PermissionService
{
    private $database;
    private $dispatcher;
    private $permission_repository;

    public function __construct(DatabaseManager $database,Dispatcher $dispatcher,PermissionRepository $permission_repository)
    {
        $this->database = $database;
        $this->dispatcher = $dispatcher;
        $this->permission_repository = $permission_repository;
    }

    public function create(array $data){
        $this->database->beginTransaction();
        try{
            $permissions = $this->permission_repository->create($data);
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $permissions;
    }

    public function get_permission_by_name($name){
        return $this->permission_repository->get_where('name',$name);
    }

    public function get_all(array $options = []){
        return $this->permission_repository->get($options);
    }
    public function get_by_id($permission_id, array $options = [])
    {
        return $this->get_requested_permission($permission_id);
    }
    private function get_requested_permission($permission_id, array $options = [])
    {
        return $this->permission_repository->get_by_id($permission_id, $options);
    }
}
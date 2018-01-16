<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/11/18
 * Time: 10:42 AM
 */

namespace App\Services;


use App\Resposities\RoleRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;

class RoleService
{
    private $database;
    private $dispatcher;
    private $role_repository;

    public function __construct(DatabaseManager $database,Dispatcher $dispatcher,
                                RoleRepository $role_repository)
    {
        $this->database = $database;
        $this->dispatcher = $dispatcher;
        $this->role_repository = $role_repository;
    }

    public function create(array $data){
        $this->database->beginTransaction();
        try{
            $role = $this->role_repository->create($data);
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $role;
    }

    public function get_role_by_name($name){
        return $this->role_repository->get_where("name",$name);
    }
    public function get_id($role_id, array $options = []){
        return $this->get_requested_role($role_id);
    }

    private function get_requested_role($role_id, array $options = []){
        return $this->role_repository->get_by_id($role_id,$options);
    }

    public function update($role_id,array  $data){
        $role = $this->get_requested_role($role_id);
        $this->database->beginTransaction();
        try{
            $this->role_repository->update($role,$data);
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $role;
    }

    public function get_all($options = []){
        return $this->role_repository->get($options);
    }
}
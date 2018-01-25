<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 2:17 AM
 */

namespace App\Services;


use App\Reposities\CompanyRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;

class CompanyService
{
    private $database;
    private $dispatcher;
    private $company_repository;

    public function __construct(DatabaseManager $database,Dispatcher $dispatcher,CompanyRepository $company_repository)
    {
        $this->database = $database;
        $this->dispatcher = $dispatcher;
        $this->company_repository = $company_repository;
    }

    public function create(array $data){
        $this->database->beginTransaction();
        try{
            $company = $this->company_repository->create($data);
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $company;
    }

    public function get_company_by_name($name){
        return $this->company_repository->get_where('name',$name);
    }

    public function get_company_by_user_id($user_id){
        return $this->company_repository->get_where('user_id',$user_id);
    }

    public function get_all(array $options = []){
        return $this->company_repository->get($options);
    }
    public function get_by_id($user_id, array $options = [])
    {
        return $this->get_requested_user($user_id);
    }
    private function get_requested_user($user_id, array $options = [])
    {
        return $this->company_repository->get_by_id($user_id, $options);
    }
}
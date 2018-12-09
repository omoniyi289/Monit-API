<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 2:17 AM
 */

namespace App\Services;


use App\Reposities\ActivationRepository;
use App\Reposities\CompanyRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;

class ActivationService
{
    private $database;
    private $dispatcher;
    private $activate_repository;

    public function __construct(DatabaseManager $database,Dispatcher $dispatcher,ActivationRepository $activate_repository)
    {
        $this->database = $database;
        $this->dispatcher = $dispatcher;
        $this->activate_repository = $activate_repository;
    }

    public function create(array $data){
        $this->database->beginTransaction();
        try{
            $company = $this->activate_repository->create($data);
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $company;
    }

    public function get_station_by_activation($is_activated,$station){
        return $this->activate_repository->get_where_array([
            [ "is_activated" ,"=", $is_activated ],
            ["station_id", "=", $station['id']]
        ]);
    }

    public function get_activation_by_id($id){
        return $this->activate_repository->get_where('id',$id);
    }

    public function get_all(array $options = []){
        return $this->activate_repository->get($options);
    }
    public function get_by_id($user_id, array $options = [])
    {
        return $this->get_requested_activation($user_id);
    }
    private function get_requested_activation($activation_id, array $options = [])
    {
        return $this->activate_repository->get_by_id($activation_id, $options);
    }
}
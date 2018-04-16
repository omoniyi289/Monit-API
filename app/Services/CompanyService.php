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
use Illuminate\Http\Request;
use App\Company;
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
     public function update($pump_id, array $data)
    {
        $pump = $this->get_requested_user($pump_id);
        $this->database->beginTransaction();
        try {
            $this->company_repository->update($pump, $data);
        } catch (Exception $exception) {
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $pump;
    }
    public function get_company_by_name($name){
        return $this->company_repository->get_where('name',$name);
    }

    public function get_company_by_user_id($user_id){
        return $this->company_repository->get_where('user_id',$user_id);
    }

    public function get_all(array $options = []){
        return Company::all();
    }
    public function get_by_id($user_id, array $options = [])
    {
        ///leave it at get, else trouble in frontend
        return Company::where('id', $user_id)->get();
    }
    public function get_for_prime_user($user_id, array $options = [])
    {
        ///leave it at get, else trouble in frontend
        
        return Company::where('user_id', $user_id)->get();
    }
    public function delete($user_id, array $options = [])
    {
        return  Company::where('id',$user_id)->delete();
    }
    public function get_company_by_reg_no($reg_no)
    {
        return $this->company_repository->get_where('registration_number',$reg_no);
    }
    private function get_requested_user($user_id, array $options = [])
    {
        return $this->company_repository->get_by_id($user_id, $options);
    }
}
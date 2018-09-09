<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/15/18
 * Time: 10:57 AM
 */

namespace App\Services;


use App\Reposities\CompanyBankAccountRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use App\CompanyBankAccount;
class CompanyBankAccountService
{
    private $database;
    private $company_bank_account_repository;
    private $dispatcher;

    public function __construct(DatabaseManager $database,CompanyBankAccountRepository $company_bank_account_repository,
                                Dispatcher $dispatcher)
    {
        $this->database = $database;
        $this->dispatcher = $dispatcher;
        $this->company_bank_account_repository = $company_bank_account_repository;
    }

    public function create($data){
        $this->database->beginTransaction();
        try{
            $company_bank_account = $this->company_bank_account_repository->create($data);
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $company_bank_account;
    }

    public function update($company_bank_account_id, array $data)
    {
        $company_bank_account = $this->get_requested_company_bank_account($company_bank_account_id);
        $this->database->beginTransaction();
        try {
            $this->company_bank_account_repository->update($company_bank_account, $data);
        } catch (Exception $exception) {
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $company_bank_account;
    }
    public function delete($company_bank_account_id, array $options = [])
    {      
        return CompanyBankAccount::where('id',$company_bank_account_id)->delete();
        
    }

    public function get_all(array $options = []){
        return $this->company_bank_account_repository->get($options);
    }

    public function get_company_bank_account_by_company_id($company_id){
        return CompanyBankAccount::where("company_id",$company_id)->orderBy('name', 'desc')->get();
    }
   
    public function get_company_bank_account_by_name($name)
    {
        return $this->company_bank_account_repository->get_where("name", $name);
    }
   
    public function get_by_id($company_bank_account_id, array $options = [])
    {
        return $this->get_requested_company_bank_account($company_bank_account_id);
    }
   
    private function get_requested_company_bank_account($company_bank_account_id, array $options = [])
    {
        return $this->company_bank_account_repository->get_by_id($company_bank_account_id, $options);
    }
}
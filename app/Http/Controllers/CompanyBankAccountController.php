<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/15/18
 * Time: 8:58 AM
 */

namespace App\Http\Controllers;
use App\interfaces\GenericInterface;
use App\Services\ActivationService;
use App\Services\CompanyService;
use App\Services\CompanyBankAccountService;
use App\Services\UserService;
use App\Util;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;

class CompanyBankAccountController extends BaseController implements GenericInterface
{
    private $company_bank_account_service;
    private $company_service;
    private $user_service;
    private $activation_service;

    public function __construct(CompanyBankAccountService $company_bank_account_service,
                                CompanyService $company_service,
                                UserService $user_service, ActivationService $activation_service)
    {
        $this->company_bank_account_service = $company_bank_account_service;
        $this->company_service = $company_service;
        $this->user_service = $user_service;
        $this->activation_service = $activation_service;
    }

    public function create(Request $request){
        $company_bank_account_req = $request->get('company_bank_account',[]);
        $user_id = $this->get_user();
      
        $exist_name = $this->company_bank_account_service->get_company_bank_account_by_name($company_bank_account_req['name']);
        if (count($exist_name) == 1){
               return $this->response(0, 8000, "error! company_bank_account with this same name already exist", $exist_name, 400);
            }

        $data = $this->company_bank_account_service->create($company_bank_account_req);
      
        return $this->response(1, 8000, "company_bank_account successfully created", $data);
    }

    public function get_by_id($company_bank_account_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->company_bank_account_service->get_by_id($company_bank_account_id,$resource_options);
        return $this->response(1, 8000, "company_bank_account details", $data);
    }

    public function get_all(){
        $resource_options = $this->parse_resource_options();
        $data = $this->company_bank_account_service->get_all($resource_options);
        return $this->response(1, 8000, "all company_bank_accounts", $data);
    }

    public function get_company_bank_account_by_company(){
        $company = $this->get_company();
        $company_company_bank_accounts = $this->company_bank_account_service->get_company_bank_account_by_company($company);
        return $this->response(1, 8000, "company_bank_account with company details", $company_company_bank_accounts);
    }
    //niyi
    public function get_company_bank_accounts_by_company_id($company_id){
        $company_company_bank_accounts = $this->company_bank_account_service->get_company_bank_account_by_company_id($company_id);
        return $this->response(1, 8000, "registered company_bank_accounts", $company_company_bank_accounts);
    }
   

    public function get_user()
    {
        return  Util::get_user_details_from_token('id');
    }

    public function get_company()
    {
        $user_id = Util::get_user_details_from_token('id');
        return $this->company_service->get_company_by_user_id($user_id)->first();
    }

    public function update($company_bank_account_id, Request $request)
    {
        $company_bank_account_update_request = $request->get('company_bank_account', []);
        $data = $this->company_bank_account_service->update($company_bank_account_id, $company_bank_account_update_request);
        return $this->response(1, 8000, "company_bank_account successfully updated", $data);
    }
     public function delete($company_bank_account_id) {
            try {
                $resource_options = $this->parse_resource_options();
                $data = $this->company_bank_account_service->delete($company_bank_account_id, $resource_options);
                return $this->response(1, 8000, "company_bank_account deleted", $data);
            }catch (Exception $exception){
                return $this->response(0, 8000, $exception->getMessage(), null,500);
            }
        }

    public function activation_code($count){
        $arr = array();
        for ($i= 0; $i <= $count; $i++){
            $arr[] = mt_rand();
        }
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/12/18
 * Time: 5:23 PM
 */

namespace App\Http\Controllers;
use App\Requests\ApiCompanyRequest;
use App\Services\CompanyService;
use App\Services\UserService;
use App\Util;
use Illuminate\Http\Request;
use Core\AuditTrail\Audit;
use Core\Controllers\BaseController;
use JWTAuth;
use Exception;
class CompanyController extends BaseController
{
    private $company_service;
    private $user_service;

    public function __construct(CompanyService $company_service,UserService $user_service)
    {
        $this->company_service = $company_service;
        $this->user_service = $user_service;
    }

    public function create(ApiCompanyRequest $request){
        try {
           // return ;
            $company_request = $request->get('company', []);
            $company_exist = $this->company_service->get_company_by_name($company_request['name']);
            $company_reg_no_exist = $this->company_service->get_company_by_reg_no($company_request['registration_number']);
            $company_request['user_id'] = Util::get_user_details_from_token('id');
            $company_request['role_id'] = Util::get_user_details_from_token('role_id');
            $user_id_exist = $this->company_service->get_company_by_user_id($company_request['user_id'])->first();
          // $user = $this->user_service->get_by_id($company_request['user_id']);
            //return $company_request['role_id'];
           if (count($user_id_exist) == 1 and $company_request['role_id'] != 'master'){
               return $this->response(0, 8000, "a company already registered with this account", $user_id_exist, 400);
            }
            if (count($company_exist) == 1) {
                return $this->response(0, 8000, "company already exist", null, 400);
            }
            if (count($company_reg_no_exist) == 1) {
                return $this->response(0, 8000, "registration number already used", null, 400);
            }
            $data = $this->company_service->create($company_request);
            // update user detail that a company is set up
            $this->user_service->update($data['user_id'],['is_company_set_up' => 1]);
//            Audit::logger("Setup Company",(string) $data['user_id'],"Company","`{$data['fullname']}` created a new company");
//            $company->logger("Setup Company","1","Company Setup","Company","1");
            return $this->response(1, 8000, "company successfully created", $data);
        }catch (Exception $exception){
            return $this->response(0, 8000, $exception->getMessage(), null,500);
        }
    }

    public function get_all(){
        try {
            $resource_options = $this->parse_resource_options();
            $data = $this->company_service->get_all($resource_options);
            return $this->response(1, 8000, "all companies", $data);
        }catch (Exception $exception){
            return $this->response(0, 8000, $exception->getMessage(), null,500);
        }
    }
    public function get_active(){
        try {
            $resource_options = $this->parse_resource_options();
            $data = $this->company_service->get_active($resource_options);
            return $this->response(1, 8000, "all companies", $data);
        }catch (Exception $exception){
            return $this->response(0, 8000, $exception->getMessage(), null,500);
        }
    }
        public function update($company_id,Request $request)
    {   try {
        $totalizer_update_request = $request->get('company', []);
        $data = $this->company_service->update($company_id,$totalizer_update_request); 
            return $this->response(1, 8000, "request successfully updated", $data);     
        }catch (Exception $exception){
            return $this->response(0, 8000, $exception->getMessage(), null,500);
        }
    }
    public function get_by_id($company_id) {
        try {
            $resource_options = $this->parse_resource_options();
            $data = $this->company_service->get_by_id($company_id, $resource_options);
            return $this->response(1, 8000, "company", $data);
        }catch (Exception $exception){
            return $this->response(0, 8000, $exception->getMessage(), null,500);
        }
    }
    
    public function get_for_prime_user($user_id) {
        try {
           // return $user_id;
            $resource_options = $this->parse_resource_options();
            $data = $this->company_service->get_for_prime_user($user_id, $resource_options);
            return $this->response(1, 8000, "company", $data);
        }catch (Exception $exception){
            return $this->response(0, 8000, $exception->getMessage(), null,500);
        }
    }

     public function delete($company_id) {
            try {
                $resource_options = $this->parse_resource_options();
                $data = $this->company_service->delete($company_id, $resource_options);
                return $this->response(1, 8000, "company deleted", $data);
            }catch (Exception $exception){
                return $this->response(0, 8000, $exception->getMessage(), null,500);
            }
        }

    public function get_token(){
        $token_details = Util::get_user_details_from_token("email");
        return $token_details;
    }


}
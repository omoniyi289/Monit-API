<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 4:23 PM
 */

namespace App\Http\Controllers;

use App\Requests\ApiCompanyUserRequest;
use App\Services\CompanyUserService;
use App\Services\UserService;
use Core\Controllers\BaseController;

class CompanyUserController extends BaseController
{
    private $company_user_service;
    private $company_service;
    public function __construct(CompanyUserService $company_user_service, UserService $company_service)
    {
        $this->company_user_service = $company_user_service;
        $this->company_service = $company_service;
    }

    public function create(ApiCompanyUserRequest $request){
        $company_user_req = $request->get('user',[]);
        $company_user_req['password'] = bcrypt("123456");
        $exist_email = $this->company_user_service->get_user_by_email($company_user_req['email']);
        $exist_username = $this->company_user_service->get_user_by_username($company_user_req['username']);
        if (count($exist_username) == 1) {
            return $this->response(0, 8009, null, null, 400);
        }
        if (count($exist_email) == 1) {
            return $this->response(0, 8010, null, null, 400);
        }
        $exist_email = $this->company_service->get_user_by_email($company_user_req['email']);
        if (count($exist_email) == 1) {
            return $this->response(0, 8010, "email already exist", null,
                JsonResponse::HTTP_BAD_REQUEST);
        }
        $data = $this->company_user_service->create($company_user_req);
        return $this->response(1, 8000, "user successfully created", $data, 201);
    }

    public function get_all(){
        $resource_options = $this->parse_resource_options();
        $data = $this->company_user_service->get_all($resource_options);
        return $this->response(1, 8000, "all users", $data);
    }
    public function get_by_company_id($company_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->company_user_service->get_by_company_id($company_id,$resource_options);
        return $this->response(1, 8000, "requested users", $data);
    }

}
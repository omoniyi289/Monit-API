<?php
/**
 * Created by PhpStorm.
 * User: omoniyiomotoso
 * Date: 1/14/18
 * Time: 4:23 PM
 */

namespace App\Http\Controllers;

use App\Services\CompanyPermissionService;
use App\Services\CompanyService;
use App\Services\UserService;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Mail\NewCompanyUserMail;
use Illuminate\Http\JsonResponse;
use Mail;
class CompanyPermissionController extends BaseController
{
    private $company_permission_service;
    private $company_service;
    private $user_service;

    public function __construct(CompanyPermissionService $company_permission_service,CompanyService $company_service, UserService $user_service)
    {
        $this->company_permission_service = $company_permission_service;
        $this->company_service = $company_service;
        $this->user_service = $user_service;
    }

    public function create(Request $request) {
        $pump_request = $request->get('permission_settings',[]);
        $data = $this->company_permission_service->create($pump_request);
        return $this->response(1, 8000, "pump successfully created", $data);
    }

    public function get_by_params(Request $request) {
    //return $request->all();
        $resource_options = $this->parse_resource_options();
        $data = $this->company_permission_service->get_by_params($request->all());
        return $this->response(1, 8000, "requested permissions", $data);
    }
     public function get_by_id($id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->company_permission_service->get_by_id($id,$resource_options);
        return $this->response(1, 8000, "requested users", $data);
    }
    public function delete($id) {
            try {
                $resource_options = $this->parse_resource_options();
                $data = $this->company_permission_service->delete($id, $resource_options);
                return $this->response(1, 8000, "permission deleted", $data);
            }catch (Exception $exception){
                return $this->response(0, 8000, $exception->getMessage(), null,500);
            }
        }

}
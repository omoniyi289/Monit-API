<?php
namespace App\Http\Controllers;

use App\Requests\ApiCompanyRequest;
use App\Requests\ApiStockReceivedRequest;
use App\Services\CompanyService;
use App\Services\RolePermissionService;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;
use PDF;

class RolePermissionController extends BaseController
{
    private $role_permission_service;

    public function __construct(RolePermissionService $role_permission_service)
    {
        $this->role_permission_service = $role_permission_service;
    }


    public function get_all(){
        $resource_options = $this->parse_resource_options();
        $data = $this->role_permission_service->get_all($resource_options);
        return $this->response(1, 8000, "perms requests", $data);
    }

  

    public function get_by_id($stock_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->role_permission_service->get_by_id($stock_id,$resource_options);
        return $this->response(1, 8000, "request details", $data);
    }
   
    public function get_by_params(Request $request) {
        $stock_update_request = $request->all();
        $resource_options = $this->parse_resource_options();
        $data = $this->role_permission_service->get_by_params($stock_update_request);
        return $this->response(1, 8000, "requested  perms", $data);
    }

}
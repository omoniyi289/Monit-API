<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/11/18
 * Time: 9:29 AM
 */

namespace App\Http\Controllers;

use App\Requests\ApiRoleRequest;
use App\Services\RoleService;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;

class RoleController extends BaseController
{
    private $role_service;

    public function __construct(RoleService $role_service)
    {
        $this->role_service = $role_service;
    }

    public function create(ApiRoleRequest $request){
        $role_request = $request->get('role',[]);
        $role_name_exit = $this->role_service->get_role_by_name($role_request['name']);
        if (count($role_name_exit) == 1){
            return $this->response(0, 8012, null, null, 400);
        }
        $data = $this->role_service->create($role_request);
        return $this->response(1, 8000, "role successfully created", $data, 201);
    }

    public function get_by_id($role_id){
        $resource_options = $this->parse_resource_options();
        $data = $this->role_service->get_id($role_id,$resource_options);
        return $this->response(1, 8000, "role details", $data);
    }
    public function get_by_company_id($company_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->role_service->get_by_company_id($company_id,$resource_options);
        return $this->response(1, 8000, "requested roles", $data);
    }

    public function get_all(){
        $resource_options = $this->parse_resource_options();
        $data = $this->role_service->get_all($resource_options);
        return $this->response(1, 8000, "all roles", $data);
    }
    public function update($role_id, Request $request){
        $role_update_request = $request->get('role',[]);
        $data = $this->role_service->update($role_id,$role_update_request);
        return $this->response(1, 8000, "role successfully updated", $data);
    }
}
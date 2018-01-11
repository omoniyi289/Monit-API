<?php
/**
 * Created by PhpStorm.
 * User: e360
 * Date: 1/11/18
 * Time: 9:29 AM
 */

namespace App\Http\Controllers;

use App\Requests\CreateRoleRequest;
use App\Services\RoleService;
use Core\Controllers\BaseController;

class RoleController extends BaseController
{
    private $role_service;

    public function __construct(RoleService $role_service)
    {
        $this->role_service = $role_service;
    }

    public function create(CreateRoleRequest $request){
        $role_request = $request->get('role',[]);
        $role_name_exit = $this->role_service->get_role_by_name($role_request['name']);
        if (!empty($role_name_exit)){
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

    public function get_all(){
        $resource_options = $this->parse_resource_options();
        $data = $this->role_service->get_all($resource_options);
        return $this->response(1, 8000, "all roles", $data);
    }
}
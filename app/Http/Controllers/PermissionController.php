<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/12/18
 * Time: 3:42 PM
 */

namespace App\Http\Controllers;

use App\Requests\ApiPermissonRequest;
use App\Services\PermissionService;
use Core\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Exception;

class PermissionController extends BaseController
{
    private $permission_service;
    public function __construct(PermissionService $permission_service)
    {
        $this->permission_service = $permission_service;
    }

    public function create(ApiPermissonRequest $request) {
        try {
            $permission_req = $request->get('permission', []);
            $permission_name_exist = $this->permission_service->get_permission_by_name($permission_req['name']);
            if (count($permission_name_exist) == 1) {
                return $this->response(0, 8000, 'permission name already exit', null,
                    JsonResponse::HTTP_BAD_REQUEST);
            }
            $data = $this->permission_service->create($permission_req);
            return $this->response(1, 8000, 'permission successfully saved', $data);
        }catch (Exception $exception){
            return $this->response(0, 8000, $exception->getMessage(), null,500);
        }
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
        $data = $this->permission_service->get_all($resource_options);
        return $this->response(1, 8000, "all permissions", $data);
    }
    public function update($role_id, Request $request){
        $role_update_request = $request->get('role',[]);
        $data = $this->role_service->update($role_id,$role_update_request);
        return $this->response(1, 8000, "role successfully updated", $data);
    }
}
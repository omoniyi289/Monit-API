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

class PermissonController extends BaseController
{
    private $permission_service;
    public function __construct(PermissionService $permission_service)
    {
        $this->permission_service = $permission_service;
    }

    public function create(ApiPermissonRequest $request) {
        $permission_req = $request->get('permission', []);
        $permission_name_exist = $this->permission_service->get_permission_by_name($permission_req['name']);
        if (count($permission_name_exist) == 1){
            return $this->response(0,8000, 'permission name already exit',null,
                JsonResponse::HTTP_BAD_REQUEST);
        }
        $data =  $this->permission_service->create($permission_req);
        return $this->response(1,8000, 'permission successfully saved', $data);
    }
}
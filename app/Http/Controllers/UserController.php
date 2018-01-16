<?php
/**
 * Created by PhpStorm.
 * User: e360
 * Date: 1/10/18
 * Time: 10:47 AM
 */

namespace App\Http\Controllers;

use App\Requests\ApiUserRolesRequest;
use App\Requests\ApiUserRequest;
use App\Services\UserService;
use Core\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    private $user_service;

    public function __construct(UserService $user_service)
    {
        $this->user_service = $user_service;
    }

    public function get_all()
    {
        // get resource options like includes,filters,page...
        // if any exist
        $resource_options = $this->parse_resource_options();
        $data = $this->user_service->get_all($resource_options);
        return $this->response(1, 8000, "all users", $data);
    }

    public function create(ApiUserRequest $request)
    {
        $user_request = $request->get('user', []);
        $user_request['password'] = bcrypt($user_request['password']);
        $exist_email = $this->user_service->get_user_by_email($user_request['email']);
        $exist_username = $this->user_service->get_user_by_username($user_request['username']);
        if (count($exist_username) == 1) {
            return $this->response(0, 8009, null, null,
                JsonResponse::HTTP_BAD_REQUEST);
        }
        if (count($exist_email) == 1) {
            return $this->response(0, 8010, null, null,
                JsonResponse::HTTP_BAD_REQUEST);
        }
        $data = $this->user_service->create($user_request);
        return $this->response(1, 8000, "user successfully created", $data,
            JsonResponse::HTTP_CREATED);
    }

    public function update($user_id, Request $request)
    {
        $user_update_request = $request->get('user', []);
        $data = $this->user_service->update($user_id, $user_update_request);
        return $this->response(1, 8000, "user successfully updated", $data);
    }

    public function get_by_id($user_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->user_service->get_by_id($user_id,$resource_options);
        return $this->response(1, 8000, "user details", $data);
    }

    public function add_roles($user_id, ApiUserRolesRequest $request){
        $roles = $request->get('roles',[]);
        $data =  $this->user_service->add_roles($user_id, $roles);
        return $this->response(1, 8000, "role successfully added", $data);
    }

}
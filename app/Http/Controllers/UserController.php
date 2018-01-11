<?php
/**
 * Created by PhpStorm.
 * User: e360
 * Date: 1/10/18
 * Time: 10:47 AM
 */

namespace App\Http\Controllers;

use App\Requests\CreateUserRequest;
use App\Services\UserService;
use Core\Controllers\BaseController;
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

    public function create(CreateUserRequest $request)
    {
        $user_request = $request->get('user', []);
        $exist_email = $this->user_service->get_user_by_email($user_request['email']);
        $exist_username = $this->user_service->get_user_by_username($user_request['username']);
        if (!empty($exist_username)) {
            return $this->response(0, 8009, null, null, 400);
        }
        if (!empty($exist_email)) {
            return $this->response(0, 8009, null, null, 400);
        }
        $data = $this->user_service->create($user_request);
        return $this->response(1, 8000, "user successfully created", $data, 201);
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
}
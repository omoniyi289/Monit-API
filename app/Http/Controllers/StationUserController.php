<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 4:23 PM
 */

namespace App\Http\Controllers;

use App\Requests\ApiStationUserRequest;
use App\Services\StationUserService;
use App\Services\UserService;
use Core\Controllers\BaseController;

class StationUserController extends BaseController
{
    private $station_user_service;
    private $user_service;
    public function __construct(StationUserService $station_user_service, UserService $user_service)
    {
        $this->station_user_service = $station_user_service;
        $this->user_service = $user_service;
    }

    public function create(ApiStationUserRequest $request){
        $station_user_req = $request->get('user',[]);
        $station_user_req['password'] = bcrypt("123456");
        $exist_email = $this->station_user_service->get_user_by_email($station_user_req['email']);
        $exist_username = $this->station_user_service->get_user_by_username($station_user_req['username']);
        if (count($exist_username) == 1) {
            return $this->response(0, 8009, null, null, 400);
        }
        if (count($exist_email) == 1) {
            return $this->response(0, 8010, null, null, 400);
        }
        $exist_email = $this->user_service->get_user_by_email($station_user_req['email']);
        if (count($exist_email) == 1) {
            return $this->response(0, 8010, "some have register with this email", null,
                JsonResponse::HTTP_BAD_REQUEST);
        }
        $data = $this->station_user_service->create($station_user_req);
        return $this->response(1, 8000, "user successfully created", $data, 201);
    }

    public function get_all(){
        $resource_options = $this->parse_resource_options();
        $data = $this->station_user_service->get_all($resource_options);
        return $this->response(1, 8000, "all stations", $data);
    }


}
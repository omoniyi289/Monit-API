<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/10/18
 * Time: 10:47 AM
 */

namespace App\Http\Controllers;

use App\Mail\RegistrationMail;
use App\Notifications\RolesAssigned;
use App\Requests\ApiUserRolesRequest;
use App\Requests\ApiUserRequest;
use App\Services\UserService;
use App\User;
use App\Util;
use Core\Controllers\BaseController;
use Core\Traits\BuilderTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mail;

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

    /**
     * @param ApiUserRequest $request
     * @return JsonResponse
     */
    public function create(ApiUserRequest $request)
    {
        $user_request = $request->get('user', []);
        $user_request['password'] = bcrypt($user_request['password']);
        $user_request['is_verified'] = false;
        $user_request['auth_key'] = str_random(6);
        $user_request['is_company_set_up'] =false;
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
        $verification_code = str_random(30);
        DB::table("user_verifications")->insert([
            'user_id' => $data['id'],
            'token' => $verification_code,
        ]);
        $mail_data = [
            'fullname' => $data['fullname'],
            'email' => $data['email'],
            'verification_code' => $verification_code,
        ];
        Mail::to($user_request['email'])->send(new RegistrationMail($mail_data));
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
        $user->notify(new RolesAssigned($data));
        return $this->response(1, 8000, "role successfully added", $data);
    }

    public function  get_users(){
        $resource_options = $this->parse_resource_options();
        $query = User::query();
        $this->apply_resource_options($query,$resource_options);
        $roles = $query->get();
        $data = $this->parse_data($roles,$resource_options);
        return $this->response(1, 8000, "users", $data);
    }

    public function verify_user($verification_code){
        $check = DB::table('user_verifications')->where('token',$verification_code)->first();
        if (!is_null($check)){
            $user = $this->user_service->get_by_id($check->user_id);
            if ($user->is_verified == 1){
                return $this->response(0, 8000, "account already verified",null);
            }
            $this->user_service->update($user->id,['is_verified' => 1]);
            DB::table('user_verifications')->where('token',$verification_code)->delete();
            return $this->response(1, 8000, "You have successfully verified your email"
                , $user);
        }
        return $this->response(0, 8000, "verification code is invalid", null);
    }
}
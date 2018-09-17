<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 4:23 PM
 */

namespace App\Http\Controllers;

use App\Requests\ApiCompanyUserRequest;
use App\Services\CompanyUserService;
use App\Services\CompanyService;
use App\Services\UserService;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Mail\NewCompanyUserMail;
use Illuminate\Http\JsonResponse;
use Mail;
class CompanyUserController extends BaseController
{
    private $company_user_service;
    private $company_service;
    private $user_service;

    public function __construct(CompanyUserService $company_user_service,CompanyService $company_service, UserService $user_service)
    {
        $this->company_user_service = $company_user_service;
        $this->company_service = $company_service;
        $this->user_service = $user_service;
    }

    public function create(ApiCompanyUserRequest $request){
        $company_user_req = $request->get('user',[]);
        $company_user_req['password'] = bcrypt("123456");
        $exist_email = $this->company_user_service->get_user_by_email($company_user_req['email']);
        $exist_username = $this->company_user_service->get_user_by_username($company_user_req['username']);
        $exist_email2 = $this->user_service->get_user_by_email($company_user_req['email']);
        $exist_username2 = $this->user_service->get_user_by_username($company_user_req['username']);
        if (count($exist_username) == 1 || count($exist_username2) == 1) {
            return $this->response(0, 8009, null, null, 400);
        }
        if (count($exist_email) == 1 or count($exist_email2) == 1 ) {
            return $this->response(0, 8010, null, null, 400);
        }
     
        $data = $this->company_user_service->create($company_user_req);
        $company= $this->company_service->get_by_id($company_user_req['company_id']);
        $mail_data = [
            'fullname' => $data['fullname'],
            'email' => $data['email'],
            'company_name' => $company[0]['name'],
        ];
        //Mail::to($company_user_req['email'])->send(new NewCompanyUserMail($mail_data));
        return $this->response(1, 8000, "user successfully created", $data, 201);
    }
    public function update($station_id, Request $request)
    {   $station_update_request = $request->get('user', []);
    
        $exist_email = $this->company_user_service->get_user_by_email($station_update_request['email']);
        $exist_username = $this->company_user_service->get_user_by_username($station_update_request['username']);

        $exist_email2 = $this->user_service->get_user_by_email($station_update_request['email']);
        $exist_username2 = $this->user_service->get_user_by_username($station_update_request['username']);

        //return  $exist_username;
        if (count($exist_username) == 1 and $exist_username[0]['id']!= $station_update_request['id']) {
            return $this->response(0, 8009, null, null, 400);
        }else if (count($exist_username2) == 1 and $exist_username2[0]['id']!= $station_update_request['id']) {
                        return $this->response(0, 8009, null, null,
                            JsonResponse::HTTP_BAD_REQUEST);
                    } 

        if (count($exist_email) == 1 and $exist_email[0]['id']!= $station_update_request['id']) {
            return $this->response(0, 8010, null, null, 400);
            }
        else if (count($exist_email2) == 1 and $exist_email2[0]['id'] != $station_update_request['id']){
                        return $this->response(0, 8010, null, null,
                            JsonResponse::HTTP_BAD_REQUEST);
                    }

        $data = $this->company_user_service->update($station_id, $station_update_request);
        return $this->response(1, 8000, "user successfully updated", $data);
    }
     public function profile_update($user_id, Request $request)
    {
        $user_request = $request->get('company_user', []);
        $password_message = '';
        if(isset($user_request['currentpassword'])){
            if(isset($user_request['password']) and isset($user_request['repeatPassword']) and isset($user_request['password']) != isset($user_request['repeatPassword'])){
             return $this->response(0, 8000, "new and repeat new password do not match",null,400);
            }else if(!isset($user_request['password']) or !isset($user_request['repeatPassword'])){
                return $this->response(0, 8000, "incomplete parameters for password change ",null,400);
            }
            $user =  $this->company_user_service->get_by_id($user_id);
            if (!empty($user) || $user != null){
                $check_password = password_verify($user_request['currentpassword'],$user['password']);
                //return $user;
                if ($check_password){
                    $user_request['password'] = bcrypt($user_request['password']);
                    $password_message=", password changed";
                   
                }else{
                     return $this->response(0, 8000, "invalid current password",null,400);
                }
            }
            }
                 $exist_email = $this->company_user_service->get_user_by_email($user_request['email']);
                 $exist_username = $this->company_user_service->get_user_by_username($user_request['username']);
                 $exist_email2 = $this->user_service->get_user_by_email($user_request['email']);
                 $exist_username2 = $this->user_service->get_user_by_username($user_request['username']);
                  //  return $exist_username2;
                    if (count($exist_username) == 1 and $exist_username[0]['id']!=$user_request['id']) {
                        return $this->response(0, 8009, null, null,
                            JsonResponse::HTTP_BAD_REQUEST);
                    }else if (count($exist_username2) == 1 and $exist_username2[0]['id']!=$user_request['id']) {
                        return $this->response(0, 8009, null, null,
                            JsonResponse::HTTP_BAD_REQUEST);
                    } 
                    //return $exist_email;
                    if (count($exist_email) == 1 and $exist_email[0]['id'] != $user_request['id']){
                        return $this->response(0, 8010, null, null,
                            JsonResponse::HTTP_BAD_REQUEST);
                    }else if (count($exist_email2) == 1 and $exist_email2[0]['id'] != $user_request['id']){
                        return $this->response(0, 8010, null, null,
                            JsonResponse::HTTP_BAD_REQUEST);
                    }
                    $data = $this->company_user_service->profile_update($user_id, $user_request);
                return $this->response(1, 8000, "user profile successfully updated".$password_message, $data);
    }


    public function get_all(){
        $resource_options = $this->parse_resource_options();
        $data = $this->company_user_service->get_all($resource_options);
        return $this->response(1, 8000, "all users", $data);
    }
    public function get_by_company_id($company_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->company_user_service->get_by_company_id($company_id,$resource_options);
        return $this->response(1, 8000, "requested users", $data);
    }
     public function get_by_id($id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->company_user_service->get_by_id($id,$resource_options);
        return $this->response(1, 8000, "requested users", $data);
    }
    public function delete($id) {
            try {
                $resource_options = $this->parse_resource_options();
                $data = $this->company_user_service->delete($id, $resource_options);
                return $this->response(1, 8000, "company deleted", $data);
            }catch (Exception $exception){
                return $this->response(0, 8000, $exception->getMessage(), null,500);
            }
        }

}
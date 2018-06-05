<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/18/18
 * Time: 12:47 AM
 */

namespace App\Http\Controllers;

use App\Services\CompanyUserService;
use App\Services\UserService;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Mail\ForgotPassMail;
use Mail;
use App\Models\UserLoginActivityLog;

class AuthController extends BaseController
{

    private $user_service;
    private $station_user_service;

    public function __construct(UserService $user_service, CompanyUserService $station_user_service)
    {
        $this->user_service = $user_service;
        $this->station_user_service = $station_user_service;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function auth(Request $request){
        $user =  $this->user_service->get_user_by_email($request->get('email'))->first();
        if (!empty($user) || $user != null){
            if ($user['is_verified'] == 0){
                return $this->response(0, 8000, "account yet to be verified",null,400);
            }
            $check_password = password_verify($request->get('password'),$user['password']);
            if ($check_password){
                $credentials = [
                    'email' => $user['email'],
                    'is_verified' => $user['is_verified'],
                    'auth_key' => $user['auth_key']
                ];
                try{
                    $token = JWTAuth::fromUser($user,$credentials);
                    $data = $user;
                    if ($token){
                        $data['token'] = $token;
                        UserLoginActivityLog::create(['email'=> $data['email'], 'user_id'=> $data['id'], 'login_time'=> date('Y-m-d H:i:s') ]);
                        return $this->response(1, 8000, "authentication successful", $data);
                    }elseif (!$token){
                        return $this->response(0, 8000, "oop!!! unable to create token with invalid credentials",
                            null, 400);
                    }
                }catch (JWTException $exception){
                    return $this->response(0, 8000, "oop!!! an error occur while processing token",
                        null,500);
                }
            }else{
                return $this->response(0, 8000, "invalid email or password",
                    null, 400);
            }
        }
          else {
      
              return $this->response(0, 8000, "user does not exist",
                     null, 400);
          }
    
    }

        public function analytics_login(Request $request){
        $user =  $this->user_service->get_user_for_analytics($request->get('email'));
        //return $user;
        if (!empty($user) || $user != null){
            if ($user['is_verified'] == 0){
                return $this->response(0, 0, "account yet to be verified",null,400);
            }
            $check_password = password_verify($request->get('password'),$user['password']);
            if ($check_password){
                $credentials = [
                    'email' => $user['email'],
                    'is_verified' => $user['is_verified'],
                    'auth_key' => $user['auth_key']
                ];
                try{
                    $token = JWTAuth::fromUser($user,$credentials);
                   // $data = $user;
                    if ($token){
                        $user['token'] = $token;

                        $station_array = array();
                        $perm_array = array();
                        foreach ($user->station_users as $key => $value) {
                          array_push($station_array, $value->station);
                        }
                        foreach ($user->role->role_permissions as $key => $value) {
                          array_push($perm_array, $value->permission['name']);
                        }
                        //return $perm_array;
                        if($user->role == null){
                            $user->role = (object)['name' => 'Nil'];
                        }

                        if($user->companies == null){
                            $user->companies = (object)['name' => 'Nil','id' => 'Nil'];
                        }

                        $data =  (object)array(["companies"=> [
                           $user->companies->id=> [
                               "name"=> $user->companies->name,
                               "stations"=> $station_array
                           ]
                       ],
                       
                       "username"=> $user['fullname'],
                       "userRole"=> $user->role->name,
                       "userPermissions"=> $perm_array,
                   "userId"=> $user['id']]);

                        return $this->response(1, 8000, "authentication successful", $data);
                    }elseif (!$token){
                        return $this->response(0, 8000, "oop!!! unable to create token with invalid credentials",
                            null, 400);
                    }
                }catch (JWTException $exception){
                    return $this->response(0, 8000, "oop!!! an error occur while processing token",
                        null,500);
                }
            }else{
                return $this->response(0, 8000, "invalid email or password",
                    null, 400);
            }
        }
          else {
      
              return $this->response(0, 8000, "user does not exist",
                     null, 400);
          }
    
    }
   public function passwordreset(Request $request){
        //= $request->get('email');
        $user =  $this->user_service->get_user_by_email($request->get('email'))->first();
        //$station_user =  $this->station_user_service->get_user_by_email($request->get('email'))->first();
        $identifier ='';
        if (!empty($user) || $user != null) {
             $data = $user;
             $identifier= 'user';
            
        }/*else if(!empty($station_user) || $station_user != null){
            $data = $station_user;
            $identifier = 'company_user';
        }*/
        else{
            return $this->response(0, 8000, "emil not found",null,400);
        }   
            $user_request=[];
             $new_pass= 'TP'.substr(uniqid(), 8);
             $user_request['password'] = bcrypt($new_pass);
             //$identifier = '';
            if($identifier == 'user'){
                $result = $this->user_service->update($user['id'],$user_request);
            }
            /*else if($identifier == 'company_user'){
                $result =  $this->station_user_service->profile_update($station_user['id'],$user_request);
            }*/
          $mail_data = [
            'fullname' => $data['fullname'],
            'email' => $data['email'],
            'new_pass' =>$new_pass,
        ];
        Mail::to($data['email'])->send(new ForgotPassMail($mail_data));
        return $this->response(1, 8000, "password successfully reset", $data);
    }
}
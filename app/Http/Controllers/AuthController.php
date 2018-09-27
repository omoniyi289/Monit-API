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
                        $var = $token;
                        $encrypt_key = $user['id'];
                        //custom cipher block chain
                        $cbc = 'E360SMREDIRECTCH';
                        $enc_token = openssl_encrypt($this->pk_pad($var, 16),'AES-256-CBC', $encrypt_key,0,$cbc);
                        $data['token_h'] = $enc_token;

                        UserLoginActivityLog::create([ 'email'=> $data['email'], 'user_id'=> $data['id'], 'app'=> 'SM', 'login_time'=> date('Y-m-d H:i:s'), 'browser_name' => $request->get('browser_name'), 'browser_version'=> $request->get('browser_version'), 'os_version' => $request->get('os_version'), 'location_cordinate' => $request->get('location_cordinate') , 'location_address' => $request->get('location_address')  , 'app' => 'SM' ]);
                        
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
       private function pk_pad($d,$s)
      {
          $len = $s-strlen($d)%$s;
          return $d.str_repeat(chr($len),$len);
      }
      private function pk_unpad($d)
      {
          return substr($d,0,-ord($d[strlen($d)-1]));
      }

        public function analytics_login(Request $request){
        $user =  $this->user_service->get_user_for_analytics($request->get('email'));
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

                    $data = $user;
                    if ($token){
                        $user['token'] = $token;
                        UserLoginActivityLog::create([ 'email'=> $data['email'], 'user_id'=> $data['id'], 'app'=> 'Analytics', 'login_time'=> date('Y-m-d H:i:s'), 'browser_name' => $request->get('browser_name'), 'browser_version'=> $request->get('browser_version'), 'os_version' => $request->get('os_version'), 'location_cordinate' => $request->get('location_cordinate') , 'location_address' => $request->get('location_address') , 'app' => 'SA' ]);

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

      public function sm_redirect_analytics_login(Request $request){
        if( $request->get('UID') == null or $request->get('Slug') ==null ){
             return $this->response(0, 8000, "missing necessary parameters",
                    null, 400);
        }
         $cbc  = 'E360SMREDIRECTCH';
         $encrypt_key = $request->get('UID');
         $enc_token =  $request->get('Slug');
         $decrypted_token = $this->pk_unpad(openssl_decrypt($enc_token,
                                                         'AES-256-CBC',
                                                         $encrypt_key,
                                                         0,
                                                         $cbc));
        if(empty($decrypted_token)){
          return $this->response(0, 8000, "invalid token or id",null,400);
        }
        else{
        $user = JWTAuth::toUser($decrypted_token);
        }

        if(empty($user) || $user == null ){
          return $this->response(0, 8000, "token not live",null,400);
        }

        $user =  $this->user_service->get_user_for_analytics($user['email']);

        if (!empty($user) || $user != null){
            if ($user['is_verified'] == 0){
                return $this->response(0, 8000, "account yet to be verified",null,400);
            }
              
              $data = $user;    
              UserLoginActivityLog::create([ 'email'=> $data['email'], 'user_id'=> $data['id'], 'app'=> 'Analytics', 'login_time'=> date('Y-m-d H:i:s'), 'browser_name' => $request->get('browser_name'), 'browser_version'=> $request->get('browser_version'), 'os_version' => $request->get('os_version'), 'location_cordinate' => $request->get('location_cordinate') , 'location_address' => $request->get('location_address') , 'app' => 'SA' ]);

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
               
        }
        else{
      
              return $this->response(0, 8000, "user does not exist",
                     null, 400);
          }
    
    }

        public function ecas_login(Request $request){
        $user =  $this->user_service->get_user_for_ecas($request->get('email'));
      
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
                        $has_ecas_permission = false;

                        foreach ($user->station_users as $key => $value) {
                          array_push($station_array, $value->station);
                        }
                        if($user->role !=null and $user->role->role_permissions !=null ){
                        foreach ($user->role->role_permissions as $key => $value) {

                          if($value->permission['name'] == 'Add and Manage Customers'){
                              $has_ecas_permission = true;
                          }
                        }
                      }
                        //return $perm_array;
                        if($user->role == null){
                            $user->role = (object)['name' => 'Nil'];
                        }

                        if($user->companies == null){
                            $user->companies = (object)['name' => 'Nil','id' => 'Nil'];
                        }


                        $data =  (object)[
                          "company_id"=> $user['company_id'],
                         "user_id"=> $user['id'],
                         "username"=> $user['fullname'],
                         "email"=> $user['email'],
                          "company_sms_sender_id"=> $user->companies->sms_sender_id,
                         "stations"=> $station_array,
                         ];

                        if(!$has_ecas_permission and $user['role_id']  != 'master'){
                          return $this->response(0, 8000, "permission not set for user", null, 400);
                        }else{
                          return $this->response(1, 8000, "authentication successful", $data);
                        }
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
            
        }


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
            
          $mail_data = [
            'fullname' => $data['fullname'],
            'email' => $data['email'],
            'new_pass' =>$new_pass,
        ];
        //Mail::to($data['email'])->send(new ForgotPassMail($mail_data));
        return $this->response(1, 8000, "password successfully reset", $data);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/18/18
 * Time: 12:47 AM
 */

namespace App\Http\Controllers;

use App\Services\UserService;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
class AuthController extends BaseController
{

    private $user_service;

    public function __construct(UserService $user_service)
    {
        $this->user_service = $user_service;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function auth(Request $request){
        $this->user_service->get_user_by_email($request->get('email'));
        if (!empty($user) || $user != null){
            if ($user[0]['is_verified'] == 0){
                return $this->response(0, 8000, "account yet to be verified",null,400);
            }
            $check_password = password_verify($request->get('password'),$user[0]['password']);
            if ($check_password){
                $credentials = [
                    'email' => $user[0]['email'],
                    'is_verified' => $user[0]['is_verified'],
                    'auth_key' => $user[0]['auth_key']
                ];
                try{
                    $token = JWTAuth::fromUser($user->first(),$credentials);
                    if ($token){
                        $data['token'] = $token;
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
        }else {
            return $this->response(0, 8000, "user does not exist ", null, 400);
        }
    }

}
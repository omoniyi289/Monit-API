<?php
/**
 * Created by PhpStorm.
 * User: e360
 * Date: 1/10/18
 * Time: 11:46 AM
 */

namespace App\Requests;

use Core\Requests\APIRequest;

class CreatUserRequest extends APIRequest
{
    public function authorize(){
        return true;
    }
    public function rules(){
        return [
            'user' => 'array|required',
            'user.fullname' => 'required|string',
            'user.email' => 'required|email',
            'user.username' => 'required|string',
            'user.password' => 'required|string|min:8',
            'user.phone_number' => 'required|string',
        ];
    }

    public function attributes()
    {
        return [
            'user.email' => 'the user\'s email'
        ];
    }
}
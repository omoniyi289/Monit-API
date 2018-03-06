<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/10/18
 * Time: 11:46 AM
 */

namespace App\Requests;

use Core\Requests\APIRequest;

class ApiStationUserRequest extends APIRequest
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
            'user.phone_number' => 'required|string',
            'user.company_id' => 'required|integer',
            'user.is_password_reset' => 'required|boolean',
        ];
    }

    public function attributes()
    {
        return [
            'user.email' => 'the user\'s email'
        ];
    }
}
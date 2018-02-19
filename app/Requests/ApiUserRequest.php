<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/10/18
 * Time: 11:46 AM
 */

namespace App\Requests;

use Core\Requests\APIRequest;

class ApiUserRequest extends APIRequest
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
            'user.password' => 'required|string|min:6',
            'user.gender' => 'required|string',
          //  'user.is_term_agreed' => 'required|boolean',
        ];
    }

    public function attributes()
    {
        return [
            'user.email' => 'the user\'s email'
        ];
    }

    public function wantsJson()
    {
        return true;
    }

    public function expectsJson()
    {
        return true;
    }

    public function validationData()
    {
      return $this->json()->all();
    }

}
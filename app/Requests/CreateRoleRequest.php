<?php
/**
 * Created by PhpStorm.
 * User: e360
 * Date: 1/10/18
 * Time: 11:46 AM
 */

namespace App\Requests;

use Core\Requests\APIRequest;

class CreateRoleRequest extends APIRequest
{
    public function authorize(){
        return true;
    }
    public function rules(){
        return [
            'role' => 'array|required',
            'role.name' => 'required|string',
        ];
    }

    public function attributes()
    {
        return [
            'role.name' => 'the role\'s name'
        ];
    }
}
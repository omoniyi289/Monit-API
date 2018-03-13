<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/10/18
 * Time: 11:46 AM
 */

namespace App\Requests;

use Core\Requests\APIRequest;

class ApiRoleRequest extends APIRequest
{
    public function authorize(){
        return true;
    }
    public function rules(){
        return [
            'role' => 'array|required',
            'role.name' => 'required|string',
            'role.description' => 'required|string',
            'role.company_id' => 'required|integer',
           
        ];
    }

    public function attributes()
    {
        return [
            'role.name' => 'the role\'s name'
        ];
    }
}
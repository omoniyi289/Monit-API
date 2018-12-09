<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/12/18
 * Time: 3:18 PM
 */

namespace App\Requests;


use Core\Requests\APIRequest;

class ApiUserRolesRequest extends APIRequest
{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'roles' => 'array|required'
        ];
    }
}
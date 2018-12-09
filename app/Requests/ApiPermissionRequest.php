<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/12/18
 * Time: 3:38 PM
 */

namespace App\Requests;


use Core\Requests\APIRequest;

class PermissionRequest extends APIRequest
{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'permission' => 'array|required',
            'permission.name' => 'string|required',
            'permission.active' => 'string|required'
        ];
    }
}
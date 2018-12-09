<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 2:04 AM
 */

namespace App\Requests;

use Core\Requests\APIRequest;

class ApiPermissonRequest extends APIRequest
{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'permission' => 'array|required',
            'permission.name' => 'required|string',
            'permission.active' => 'required|boolean',
        ];
    }

}
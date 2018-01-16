<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 2:04 AM
 */

namespace App\Requests;


use Core\Requests\APIRequest;

class ApiPumpGroupsRequest extends APIRequest
{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'pump_group' => 'array|required',
            'pump_group.code' => 'required|string',
            'pump_group.name' => 'required|email',
        ];
    }
    public function attributes()
    {
        return [
            'pump_group.name' => 'the user\'s email'
        ];
    }
}
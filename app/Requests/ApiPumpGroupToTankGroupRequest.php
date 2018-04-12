<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 2:04 AM
 */

namespace App\Requests;


use Core\Requests\APIRequest;

class ApiPumpGroupToTankGroupRequest extends APIRequest
{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
           'p_t_group' => 'array|required',
           'p_t_group.pump_group_id' => 'required|integer',
           'p_t_group.name' => 'required|string',
        ];
    }
    public function attributes()
    {
        return [
            'p_t_group.name' => ''
        ];
    }
}
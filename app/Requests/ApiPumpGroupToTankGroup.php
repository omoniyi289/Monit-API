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
            'pump_group_to_tank_group' => 'array|required',
            'pump_group_to_tank_group.pump_group' => 'required|integer',
            'pump_group_to_tank_group.name' => 'required|string',
        ];
    }
    public function attributes()
    {
        return [
            'pump_group_to_tank_group.name' => ''
        ];
    }
}
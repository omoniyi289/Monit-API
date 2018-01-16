<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 2:04 AM
 */

namespace App\Requests;


use Core\Requests\APIRequest;

class ApiTankGroupRequest extends APIRequest
{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'tank_group' => 'array|required',
            'tank_group.name' => 'required|string',
            'tank_group.code' => 'required|string',
            'tank_group.company_id' => 'required|integer',
            'tank_group.station_id' => 'required|integer',
        ];
    }
    public function attributes()
    {
        return [
            'tank_group.code' => 'the user\'s code'
        ];
    }
}
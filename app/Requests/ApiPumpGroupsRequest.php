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
            'pump_group.name' => 'required|string',
            'pump_group.selected_pumps' => 'array|required',
            'pump_group.station_id' => 'required|integer',
            'pump_group.company_id' => 'required|integer',
        ];
    }
    public function attributes()
    {
        return [
            'pump_group.name' => 'the user\'s email'
        ];
    }
}
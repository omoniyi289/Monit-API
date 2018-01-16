<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 2:04 AM
 */

namespace App\Requests;


use Core\Requests\APIRequest;

class ApiPumpsRequest extends APIRequest
{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'pumps' => 'array|required',
            'pump_group.name' => 'required|string',
            'pump_group.code' => 'required|string',
            'pump_group.station_id' => 'required|integer',
            'pump_group.company_id' => 'required|integer',
        ];
    }

}
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
            'pump' => 'array|required',
            'pump.number' => 'required|string',
            'pump.brand' => 'required|string',
            'pump.serial_number' => 'required|string',
            'pump.type' => 'required|string',
            //'pump.pump_group_id' => 'required|integer',
            'pump.station_id' => 'required|integer',
            'pump.company_id' => 'required|integer',
        ];
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/15/18
 * Time: 10:48 AM
 */

namespace App\Requests;
use Core\Requests\APIRequest;

class ApiStationRequest extends APIRequest
{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'station' => 'array|required',
            'station.name' => 'required|string',
            //'station.manager_email' => 'required|string',
            //'station.manager_phone' => 'string',
            //'station.address' => 'required|string',
            //'station.opening_time' => 'required|string',
            'station.city' => 'required|string',
            //'station.state' => 'required|string',
            //'station.daily_budget' => 'required|string',
            //'station.expenses_type' => 'required|string',
            'station.company_id' => 'required|integer',
            //'station.monthly_budget' => 'required|string',
           // 'station.license_type' => 'required|string',
        ];
    }
}
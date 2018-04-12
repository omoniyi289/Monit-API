<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 2:04 AM
 */

namespace App\Requests;


use Core\Requests\APIRequest;

class ApiTankRequest extends APIRequest
{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'tank' => 'array|required',
            'tank.code' => 'required|string',
            //'tank.name' => 'required|string',
            'tank.width' => 'string',
            'tank.height' => 'string',
            'tank.type' => 'string',
            'tank.capacity' => 'string',
            'tank.reorder_volume' => 'required|string',
            'tank.deadstock' => 'required|string',
            //'tank.max_temperate' => 'required|string',
            'tank.max_water_level' => 'required|integer',
            'tank.station_id' => 'required|integer',
            'tank.company_id' => 'required|integer',
            'tank.product_id' => 'required|integer',
        ];
    }
    public function attributes()
    {
        return [
            'tank_group.code' => 'the user\'s code'
        ];
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 2:04 AM
 */

namespace App\Requests;


use Core\Requests\APIRequest;

class ApiProductsPriceRequest extends APIRequest
{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'product_change_log' => 'array|required',
            'product_change_log.requested_price_tag' => 'required|string',
            //'product_change_log.updated_by' => 'required|integer',
            'product_change_log.company_id' => 'required|integer',
            'product_change_log.station_id' => 'required|integer',
            'product_change_log.product_id' => 'required|integer',
        ];
    }
}
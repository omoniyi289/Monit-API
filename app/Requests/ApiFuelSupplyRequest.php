<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/10/18
 * Time: 11:46 AM
 */

namespace App\Requests;

use Core\Requests\APIRequest;

class ApiFuelSupplyRequest extends APIRequest
{
    public function authorize(){
        return true;
    }
    public function rules(){
        return [
            'fuel_request' => 'array|required',
            
            'fuel_request.created_by' => 'required|integer',
            'fuel_request.product_id' => 'required|integer',
            'fuel_request.company_id' => 'required|integer',
            'fuel_request.station_id' => 'required|integer',
            'fuel_request.quantity_requested'=> 'required|integer'
           
        ];
    }

    public function attributes()
    {
        return [
            'user.email' => 'the user\'s email'
        ];
    }
}
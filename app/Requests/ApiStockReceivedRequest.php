<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/10/18
 * Time: 11:46 AM
 */

namespace App\Requests;

use Core\Requests\APIRequest;

class ApiStockReceivedRequest extends APIRequest
{
    public function authorize(){
        return true;
    }
    public function rules(){
        return [
            'stock_received' => 'array|required',
            
            'stock_received.stock_received_by' => 'required|integer',
            'stock_received.product_id' => 'required|integer',
            'stock_received.company_id' => 'required|integer',
            'stock_received.station_id' => 'required|integer',
            'stock_received.quantity_supplied'=> 'required|integer'
           
        ];
    }

    public function attributes()
    {
        return [
            'user.email' => 'the user\'s email'
        ];
    }
}
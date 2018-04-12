<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/10/18
 * Time: 11:46 AM
 */

namespace App\Requests;

use Core\Requests\APIRequest;

class ApiDepositsRequest extends APIRequest
{
    public function authorize(){
        return true;
    }
    public function rules(){
        return [
            'deposits' => 'array|required',
            
            'deposits.created_by' => 'required|integer',
            'deposits.company_id' => 'required|integer',
            'deposits.station_id' => 'required|integer',
            'deposits.amount'=> 'required|integer',
            'deposits.date' =>'required|string',
            'deposits.payment_type' =>'required|string'
           
        ];
    }

    public function attributes()
    {
        return [
            'user.email' => 'the user\'s email'
        ];
    }
}
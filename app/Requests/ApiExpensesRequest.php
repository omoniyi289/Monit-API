<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/10/18
 * Time: 11:46 AM
 */

namespace App\Requests;

use Core\Requests\APIRequest;

class ApiExpensesRequest extends APIRequest
{
    public function authorize(){
        return true;
    }
    public function rules(){
        return [
            'expenses' => 'array|required',
            
            'expenses.description' => 'required|string',
            'expenses.company_id' => 'required|integer',
            'expenses.station_id' => 'required|integer',
            'expenses.amount'=> 'required|string',
            'expenses.date' =>'required|string'
           
        ];
    }

    public function attributes()
    {
        return [
            'user.email' => 'the user\'s email'
        ];
    }
}
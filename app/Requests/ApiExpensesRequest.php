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
            'expense_header' => 'array|required',
            
            'expense_header.company_id' => 'required|integer',
            'expense_header.station_id' => 'required|integer',
            'expense_header.total_amount'=> 'required|string',
            'expense_header.expense_date' =>'required|string'
           
        ];
    }

    public function attributes()
    {
        return [
            'user.email' => 'the user\'s email'
        ];
    }
}
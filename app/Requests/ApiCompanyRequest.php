<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 2:04 AM
 */

namespace App\Requests;


use Core\Requests\APIRequest;

class ApiCompanyRequest extends APIRequest
{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'company' => 'array|required',
            'company.name' => 'required|string',
            'company.email' => 'required|email',
            'company.registration_number' => 'required|string',
            'company.country' => 'required|string',
            'company.state' => 'required|string',
            'company.city' => 'required|string',
            'company.address' => 'required|string',
            'company.user_id' => 'required|integer',
        ];
    }
    public function attributes()
    {
        return [
            'company.email' => 'the user\'s email'
        ];
    }
}
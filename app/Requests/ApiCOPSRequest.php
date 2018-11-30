<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/10/18
 * Time: 11:46 AM
 */

namespace App\Requests;

use Core\Requests\APIRequest;

class ApiCOPSRequest extends APIRequest
{
    public function authorize(){
        return true;
    }
    public function rules(){
        return [
            'cops' => 'array|required',
            'cops.company_id' => 'required|integer',
            'cops.uploaded_by' => 'required|integer',
            'cops.survey_date' => 'required|string'
        ];
    }

    public function attributes()
    {
        return [
            'cops.email' => 'the user\'s email'
        ];
    }
}
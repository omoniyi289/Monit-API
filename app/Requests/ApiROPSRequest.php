<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/10/18
 * Time: 11:46 AM
 */

namespace App\Requests;

use Core\Requests\APIRequest;

class ApiROPSRequest extends APIRequest
{
    public function authorize(){
        return true;
    }
    public function rules(){
        return [
            'rops' => 'array|required',
            'rops.station_id' => 'required|integer',
            'rops.company_id' => 'required|integer',
            'rops.uploaded_by' => 'required|integer',
            'rops.survey_date' => 'required|string'
        ];
    }

    public function attributes()
    {
        return [
            'rops.email' => 'the user\'s email'
        ];
    }
}
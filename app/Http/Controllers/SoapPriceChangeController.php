<?php
namespace App\Http\Controllers;

use App\Requests\ApiDepositsRequest;
use App\Services\DepositsService;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;

class SoapPriceChangeController extends BaseController
{
    private $soap_change_service;

    public function __construct(SoapPriceChangeService $soap_change_service)
    {
        $this->soap_change_service = $soap_change_service;
    }

   public function getCurrency(Request $request) {
      $response = array();
      $parameters = $request->json()->all();
      
      $rules =  array(
            'name'    => 'required'
        );
        
        $messages = array(
            'name.required' => 'name is required.'
        );
      
      $validator = \Validator::make(array('name' => $parameters['name']), $rules, $messages);
 
      if(!$validator->fails()) {
            $response = $this->soap_change_service->getCurrency($parameters);
            return response;
         } else {
            $errors = $validator->errors();
            return response()->json($errors->all());
         }
      
   }

}
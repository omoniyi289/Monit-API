<?php
/**
 * Created by PhpStorm.
 * User: omoniyiomotoso
 * Date: 1/14/18
 * Time: 4:23 PM
 */

namespace App\Http\Controllers;

use App\Services\VeloxCustomerService;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Mail\NewCompanyUserMail;
use Illuminate\Http\JsonResponse;
use Mail;
class VeloxCustomerController extends BaseController
{
    private $velox_customer_service;

    public function __construct(VeloxCustomerService $velox_customer_service)
    {
        $this->velox_customer_service = $velox_customer_service;
    }

    public function get_by_params(Request $request) {
    //return 43677;
        $resource_options = $this->parse_resource_options();
        $data = $this->velox_customer_service->get_by_params($request->all());
        return $this->response(1, 8000, "requested vendors", $data);
    }
   
       public function update(Request $request)
    {
        $update_request = $request->all();
        $data = $this->velox_customer_service->update($update_request);
       
        return $this->response(1, 8000, "request successfully updated", $data);     
    
    }
 

}
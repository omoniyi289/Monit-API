<?php
/**
 * Created by PhpStorm.
 * User: omoniyiomotoso
 * Date: 1/14/18
 * Time: 4:23 PM
 */

namespace App\Http\Controllers;

use App\Services\VeloxPaymentService;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Mail\NewCompanyUserMail;
use Illuminate\Http\JsonResponse;
use Mail;
class VeloxPaymentController extends BaseController
{
    private $velox_payment_service;

    public function __construct(VeloxPaymentService $velox_payment_service)
    {
        $this->velox_payment_service = $velox_payment_service;
    }

    public function create(Request $request) {
       // return $request->all();
        $cv_request = $request->all();
        $data = $this->velox_payment_service->create($cv_request);
        return $this->response(1, 8000, "cv successfully created", $data);
    }

    public function get_by_params(Request $request) {
    //return $request->all();
        $resource_options = $this->parse_resource_options();
        $data = $this->velox_payment_service->get_by_params($request->all());
        return $this->response(1, 8000, "requested vendors", $data);
    }
    
    public function get_by_id($id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->velox_payment_service->get_by_id($id,$resource_options);
        return $this->response(1, 8000, "requested cv", $data);
    }
    
    public function update(Request $request)
    {
        $update_request = $request->all();
        $data = $this->velox_payment_service->update($update_request);
       
        return $this->response(1, 8000, "request successfully updated", $data);     
    
    }

     // public function verify_approval(Request $request) {
     //    $stock_update_request = $request->all();
     //    $data = $this->product_price_change_logs_service->verify_approval($stock_update_request);
     //    //return $data;
     //    if($data['statusCode'] == 1)
     //        return $this->response(1, 8000, "request details", $data);
     //    else
     //        return $this->response(0, 8000,$data['message'] , $data, 400);
     // }



}
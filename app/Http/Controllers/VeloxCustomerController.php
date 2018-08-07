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

    public function create(Request $request) {
       // return $request->all();
        $cv_request = $request->get('velox_customer');
        $data = $this->velox_customer_service->create($cv_request);
        return $this->response(1, 8000, "cv successfully created", $data);
    }

    public function get_by_params(Request $request) {
    //return $request->all();
        $resource_options = $this->parse_resource_options();
        $data = $this->velox_customer_service->get_by_params($request->all());
        return $this->response(1, 8000, "requested vendors", $data);
    }
     public function get_by_id($id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->velox_customer_service->get_by_id($id,$resource_options);
        return $this->response(1, 8000, "requested cv", $data);
    }
       public function update(Request $request)
    {
        $update_request = $request->get('velox_customer', []);
        $data = $this->velox_customer_service->update($update_request);
       
        return $this->response(1, 8000, "request successfully updated", $data);     
    
    }
    public function delete($id) {
            try {
                $resource_options = $this->parse_resource_options();
                $data = $this->velox_customer_service->delete($id, $resource_options);
                return $this->response(1, 8000, "vendor deleted", $data);
            }catch (Exception $exception){
                return $this->response(0, 8000, $exception->getMessage(), null,500);
            }
        }

}
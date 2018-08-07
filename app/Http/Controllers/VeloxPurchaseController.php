<?php
/**
 * Created by PhpStorm.
 * User: omoniyiomotoso
 * Date: 1/14/18
 * Time: 4:23 PM
 */

namespace App\Http\Controllers;

use App\Services\VeloxPurchaseService;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Mail\NewCompanyUserMail;
use Illuminate\Http\JsonResponse;
use Mail;
class VeloxPurchaseController extends BaseController
{
    private $velox_purchase_service;

    public function __construct(VeloxPurchaseService $velox_purchase_service)
    {
        $this->velox_purchase_service = $velox_purchase_service;
    }

  

    public function get_by_params(Request $request) {
    //return $request->all();
        $resource_options = $this->parse_resource_options();
        $data = $this->velox_purchase_service->get_by_params($request->all());
        return $this->response(1, 8000, "requested vendors", $data);
    }
     public function get_by_id($id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->velox_purchase_service->get_by_id($id,$resource_options);
        return $this->response(1, 8000, "requested cv", $data);
    }

}
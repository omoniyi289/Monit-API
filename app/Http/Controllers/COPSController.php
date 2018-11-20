<?php
namespace App\Http\Controllers;

use App\Requests\ApiCOPSRequest;
use App\Services\COPSService;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;

class COPSController extends BaseController
{
    private $cops_service;

    public function __construct(COPSService $cops_service)
    {
        $this->cops_service = $cops_service;
    }

    public function create(ApiCOPSRequest $request) {
         //return $request;

        $cops_request = $request->get('cops',[]);
       // $survey_date = date_format(date_create($cops_request['survey_date']),"Y-m-d");
        $surveys = $this->cops_service->get_by_params($cops_request);
        if(count($surveys) > 0){
             return $this->response(0, 8000, "Oops! survey already submitted for selected date",null,400);
          }

        $data = $this->cops_service->create($cops_request);
        //return $this->response(0, 8000,$data,null,400);
        return $this->response(1, 8000, "cops successfully created", $data);
    }

    public function get_all(){
        $resource_options = $this->parse_resource_options();
        $data = $this->cops_service->get_all($resource_options);
        return $this->response(1, 8000, "cops request", $data);
    }

    public function get_by_id($stock_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->cops_service->get_by_id($stock_id,$resource_options);
        return $this->response(1, 8000, "request details", $data);
    }
   
    public function get_by_params(Request $request) {
        $params = $request->all();
        $data = $this->cops_service->get_by_params($params);
        return $this->response(1, 8000, "requested cops", $data);
    }
   


}
<?php
namespace App\Http\Controllers;

use App\Requests\ApiROPSRequest;
use App\Services\ROPSService;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;

class ROPSController extends BaseController
{
    private $rops_service;

    public function __construct(ROPSService $rops_service)
    {
        $this->rops_service = $rops_service;
    }

    public function create(ApiROPSRequest $request) {
         //return $request;

        $rops_request = $request->get('rops',[]);
       // $survey_date = date_format(date_create($rops_request['survey_date']),"Y-m-d");
        $surveys = $this->rops_service->get_by_params($rops_request);
        if(count($surveys) > 0){
             return $this->response(0, 8000, "Oops! survey already submitted for today",null,400);
          }

        $data = $this->rops_service->create($rops_request);
        //return $this->response(0, 8000,$data,null,400);
        return $this->response(1, 8000, "deposits successfully created", $data);
    }

    public function get_all(){
        $resource_options = $this->parse_resource_options();
        $data = $this->rops_service->get_all($resource_options);
        return $this->response(1, 8000, "rops request", $data);
    }

    public function get_by_id($stock_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->rops_service->get_by_id($stock_id,$resource_options);
        return $this->response(1, 8000, "request details", $data);
    }
   
    public function get_by_params(Request $request) {
        $params = $request->all();
        $data = $this->rops_service->get_by_params($params);
        return $this->response(1, 8000, "requested rops", $data);
    }
   


}
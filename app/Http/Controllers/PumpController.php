<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/12/18
 * Time: 5:23 PM
 */

namespace App\Http\Controllers;
use App\Requests\ApiCompanyRequest;
use App\Requests\ApiPumpsRequest;
use App\Services\CompanyService;
use App\Services\PumpService;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;

class PumpController extends BaseController
{
    private $pump_service;

    public function __construct(PumpService $pump_service)
    {
        $this->pump_service = $pump_service;
    }

    public function create(ApiPumpsRequest $request) {
        $pump_request = $request->get('pump',[]);
        $exist_name = $this->pump_service->get_pump_by_code($pump_request['pump_nozzle_code'], $pump_request['station_id']);
        if (count($exist_name) == 1){
               return $this->response(0, 8000, "error! pump with this same code already exist", $exist_name, 400);
            }
        $data = $this->pump_service->create($pump_request);
        return $this->response(1, 8000, "pump successfully created", $data);
    }
      public function update($pump_id, Request $request)
    {
        $pump_update_request = $request->get('pump', []);
        $data = $this->pump_service->update($pump_id, $pump_update_request);
        return $this->response(1, 8000, "pump successfully updated", $data);
    }

    public function get_all(){
        $resource_options = $this->parse_resource_options();
        $data = $this->pump_service->get_all($resource_options);
        return $this->response(1, 8000, "pumps", $data);
    }

    public function get_by_id($pump_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->pump_service->get_by_id($pump_id,$resource_options);
        return $this->response(1, 8000, "pump details", $data);
    }
     public function get_by_station_id($station_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->pump_service->get_by_station_id($station_id,$resource_options);
        return $this->response(1, 8000, "requested pumps", $data);
    }
    public function delete($pump_id) {
            try {
                $resource_options = $this->parse_resource_options();
                $data = $this->pump_service->delete($pump_id, $resource_options);
                return $this->response(1, 8000, "pump deleted", $data);
            }catch (Exception $exception){
                return $this->response(0, 8000, $exception->getMessage(), null,500);
            }
        }

}
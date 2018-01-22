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

class PumpController extends BaseController
{
    private $pump_service;

    public function __construct(PumpService $pump_service)
    {
        $this->pump_service = $pump_service;
    }

    public function create(ApiPumpsRequest $request) {
        $pump_request = $request->get('pump',[]);
        $data = $this->pump_service->create($pump_request);
        return $this->response(1, 8000, "pump successfully created", $data);
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

}
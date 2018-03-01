<?php

namespace App\Http\Controllers;
use App\Requests\ApiPumpGroupToTankGroup;
use App\Services\PumpGroupToTankGroupService;
use Core\Controllers\BaseController;

class PumpGroupToTankGroupController extends BaseController
{
    private $pump_group_to_tank_group_service;

    public function __construct(ApiPumpGroupToTankGroup $pump_group_to_tank_group_service)
    {
        $this->pump_group_to_tank_group_service = $pump_group_to_tank_group_service;
    }

    public function create(ApiPumpGroups $request) {
        $company_request = $request->get('pump_group',[]);
        $data = $this->pump_group_to_tank_group_service->create($company_request);
        return $this->response(1, 8000, "Pump group successfully created", $data);
    }

    public function get_all(){
        $resource_options = $this->parse_resource_options();
        $data = $this->pump_group_to_tank_group_service->get_all($resource_options);
        return $this->response(1, 8000, "all pump groups", $data);
    }
    public function get_by_id($pump_group_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->pump_group_to_tank_group_service->get_by_id($pump_group_id,$resource_options);
        return $this->response(1, 8000, "pump group details", $data);
    }

}
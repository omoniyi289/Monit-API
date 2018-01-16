<?php
/**
 * Created by PhpStorm.
 * User: e360
 * Date: 1/12/18
 * Time: 5:23 PM
 */

namespace App\Http\Controllers;
use App\Requests\ApiCompanyRequest;
use App\Requests\ApiPumpGroups;
use App\Services\CompanyService;
use App\Services\PumpGroupService;
use Core\Controllers\BaseController;

class PumpGroupsController extends BaseController
{
    private $pump_groups_service;

    public function __construct(PumpGroupService $pump_groups_service)
    {
        $this->pump_groups_service = $pump_groups_service;
    }

    public function create(ApiPumpGroups $request) {
        $company_request = $request->get('pump_group',[]);
        $data = $this->pump_groups_service->create($company_request);
        return $this->response(1, 8000, "Pump group successfully created", $data);
    }

    public function get_all(){
        $resource_options = $this->parse_resource_options();
        $data = $this->pump_groups_service->get_all($resource_options);
        return $this->response(1, 8000, "all pump groups", $data);
    }
    public function get_by_id($pump_group_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->pump_groups_service->get_by_id($pump_group_id,$resource_options);
        return $this->response(1, 8000, "pump group details", $data);
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/12/18
 * Time: 5:23 PM
 */

namespace App\Http\Controllers;
use App\Requests\ApiCompanyRequest;
use App\Requests\ApiTankGroupRequest;
use App\Services\CompanyService;
use App\Services\TankGroupService;
use Core\Controllers\BaseController;

class TankGroupsController extends BaseController
{
    private $tank_group_service;

    public function __construct(TankGroupService $tank_group_service)
    {
        $this->tank_group_service = $tank_group_service;
    }

    public function create(ApiTankGroupRequest $request){
        $tank_group_request = $request->get('tank_group',[]);
        $data = $this->tank_group_service->create($tank_group_request);
        return $this->response(1, 8000, "tank group successfully created", $data);
    }

    public function get_all(){
        $resource_options = $this->parse_resource_options();
        $data = $this->tank_group_service->get_all($resource_options);
        return $this->response(1, 8000, "all tank groups", $data);
    }
    public function get_by_id($tank_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->tank_group_service->get_by_id($tank_id,$resource_options);
        return $this->response(1, 8000, "tank group details", $data);
    }

}
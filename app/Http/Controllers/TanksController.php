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
use App\Requests\ApiTankRequest;
use App\Services\CompanyService;
use App\Services\TankGroupService;
use App\Services\TankService;
use Core\Controllers\BaseController;

class TanksController extends BaseController
{
    private $tank_service;

    public function __construct(TankService $tank_service)
    {
        $this->tank_service = $tank_service;
    }

    public function create(ApiTankRequest $request){
        $tank_request = $request->get('tank',[]);
        $data = $this->tank_service->create($tank_request);
        return $this->response(1, 8000, "tank successfully created", $data);
    }

    public function get_all(){
        $resource_options = $this->parse_resource_options();
        $data = $this->tank_service->get_all($resource_options);
        return $this->response(1, 8000, "all tanks", $data);
    }
    public function get_by_id($tank_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->tank_service->get_by_id($tank_id,$resource_options);
        return $this->response(1, 8000, "tank details", $data);
    }

}
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
use App\Services\VeloxPumpService;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;

class VeloxPumpController extends BaseController
{
    private $pump_service;

    public function __construct(VeloxPumpService $pump_service)
    {
        $this->pump_service = $pump_service;
    }

    public function get_by_id($pump_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->pump_service->get_by_id($pump_id,$resource_options);
        return $this->response(1, 8000, "pump details", $data);
    }
     public function get_by_params(Request $request) {
        $data = $this->pump_service->get_by_params($request->all());
        return $this->response(1, 8000, "requested pumps", $data);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/15/18
 * Time: 8:58 AM
 */

namespace App\Http\Controllers;
use App\Requests\ApiStationRequest;
use App\Services\StationService;
use Core\Controllers\BaseController;

class StationController extends BaseController
{
    private $station_service;

    public function __construct(StationService $station_service)
    {
        $this->station_service = $station_service;
    }

    public function create(ApiStationRequest $request){
        $station_req = $request->get('station',[]);
        $data = $this->station_service->create($station_req);
        return $this->response(1, 8000, "station successfully created", $data);
    }

    public function get_all(){
        $resource_options = $this->parse_resource_options();
        $data = $this->station_service->get_all($resource_options);
        return $this->response(1, 8000, "all stations", $data);
    }
}
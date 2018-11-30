<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/11/18
 * Time: 9:29 AM
 */

namespace App\Http\Controllers;

use App\Requests\ApiRegionRequest;
use App\Services\RegionService;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;

class RegionController extends BaseController
{
    private $region_service;

    public function __construct(RegionService $region_service)
    {
        $this->region_service = $region_service;
    }

    public function create(ApiRegionRequest $request){
        $region_request = $request->get('region',[]);
        $region_name_exit = $this->region_service->get_region_by_name($region_request['name'], $region_request['company_id']);
        if (count($region_name_exit) == 1){
            return $this->response(0, 8014, null, null, 400);
        }
        $data = $this->region_service->create($region_request);
        return $this->response(1, 8000, "region successfully created", $data, 201);
    }

    public function get_by_id($region_id){
        $resource_options = $this->parse_resource_options();
        $data = $this->region_service->get_id($region_id,$resource_options);
        return $this->response(1, 8000, "region details", $data);
    }
    public function get_by_company_id($company_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->region_service->get_by_company_id($company_id,$resource_options);
        return $this->response(1, 8000, "requested regions", $data);
    }
     public function  get_region_permissions($region_id){
        $data =  $this->region_service->get_region_permissions($region_id);
        return $this->response(1, 8000, "user permissions", $data);
    }
    public function delete($region_id) {
            try {
                $resource_options = $this->parse_resource_options();
                $data = $this->region_service->delete($region_id, $resource_options);
                return $this->response(1, 8000, "region deleted", $data);
            }catch (Exception $exception){
                return $this->response(0, 8000, $exception->getMessage(), null,500);
            }
        }

    public function get_all(){
        $resource_options = $this->parse_resource_options();
        $data = $this->region_service->get_all($resource_options);
        return $this->response(1, 8000, "all regions", $data);
    }
    public function update($region_id, Request $request){
        $region_update_request = $request->get('region',[]);
        $data = $this->region_service->update($region_id,$region_update_request);
        return $this->response(1, 8000, "region successfully updated", $data);
    }
}
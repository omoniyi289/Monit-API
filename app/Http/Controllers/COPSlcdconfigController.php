<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/11/18
 * Time: 9:29 AM
 */

namespace App\Http\Controllers;

use App\Requests\ApiCOPSlcdconfigRequest;
use App\Services\COPSlcdconfigService;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;

class COPSlcdconfigController extends BaseController
{
    private $COPSlcdconfig_service;

    public function __construct(COPSlcdconfigService $COPSlcdconfig_service)
    {
        $this->COPSlcdconfig_service = $COPSlcdconfig_service;
    }

    public function create(Request $request){
        $COPSlcdconfig_request = $request->get('cops_lcd_config',[]);
        $COPSlcdconfig_name_exit = $this->COPSlcdconfig_service->get_COPSlcdconfig_by_name($COPSlcdconfig_request['name'], $COPSlcdconfig_request['company_id']);
        if (count($COPSlcdconfig_name_exit) == 1){
            return $this->response(0, 8012, null, null, 400);
        }
        $data = $this->COPSlcdconfig_service->create($COPSlcdconfig_request);
        return $this->response(1, 8000, "COPS Config successfully created", $data, 201);
    }

    public function get_by_id($COPSlcdconfig_id){
        $resource_options = $this->parse_resource_options();
        $data = $this->COPSlcdconfig_service->get_id($COPSlcdconfig_id,$resource_options);
        return $this->response(1, 8000, "COPSlcdconfig details", $data);
    }
    public function get_by_company_id($company_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->COPSlcdconfig_service->get_by_company_id($company_id,$resource_options);
        return $this->response(1, 8000, "requested COPSlcdconfigs", $data);
    }

    public function delete($COPSlcdconfig_id) {
            try {
                $resource_options = $this->parse_resource_options();
                $data = $this->COPSlcdconfig_service->delete($COPSlcdconfig_id, $resource_options);
                return $this->response(1, 8000, "COPSlcdconfig deleted", $data);
            }catch (Exception $exception){
                return $this->response(0, 8000, $exception->getMessage(), null,500);
            }
        }

    public function get_all(){
        $resource_options = $this->parse_resource_options();
        $data = $this->COPSlcdconfig_service->get_all($resource_options);
        return $this->response(1, 8000, "all COPSlcdconfigs", $data);
    }
    public function update($COPSlcdconfig_id, Request $request){
        $COPSlcdconfig_update_request = $request->get('cops_lcd_config',[]);
        $data = $this->COPSlcdconfig_service->update($COPSlcdconfig_id,$COPSlcdconfig_update_request);
        return $this->response(1, 8000, "COPS Config successfully updated", $data);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: e360
 * Date: 1/12/18
 * Time: 5:23 PM
 */

namespace App\Http\Controllers;
use App\Requests\ApiCompanyRequest;
use App\Services\CompanyService;
use Core\Controllers\BaseController;

class CompanyController extends BaseController
{
    private $company_service;

    public function __construct(CompanyService $company_service)
    {
        $this->company_service = $company_service;
    }

    public function create(ApiCompanyRequest $request){
        $company_request = $request->get('company',[]);
        $company_exist = $this->company_service->get_company_by_name($company_request['name']);
        if (count($company_exist) == 1) {
            return $this->response(0, 8000, "company already exist", null,400);
        }
        $data = $this->company_service->create($company_request);
        return $this->response(1, 8000, "company successfully created", $data);
    }

    public function get_all(){
        $resource_options = $this->parse_resource_options();
        $data = $this->company_service->get_all($resource_options);
        return $this->response(1, 8000, "all companies", $data);
    }
    public function get_by_id($company_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->company_service->get_by_id($company_id,$resource_options);
        return $this->response(1, 8000, "company details", $data);
    }

}
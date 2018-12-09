<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/12/18
 * Time: 5:23 PM
 */

namespace App\Http\Controllers;
use App\Requests\ApiCompanyRequest;
use App\Requests\ApiProductsRequest;
use App\Services\CompanyService;
use App\Services\ProductService;
use Core\Controllers\BaseController;

class ProductsController extends BaseController
{
    private $product_service;

    public function __construct(ProductService $product_service)
    {
        $this->product_service = $product_service;
    }

    public function create(ApiProductsRequest $request){
        $product_request = $request->get('product',[]);
        $product_exist = $this->product_service->get_product_by_name($product_request['name']);
        if (count($product_exist) == 1) {
            return $this->response(0, 8000, "product already exist", null,400);
        }
        $data = $this->product_service->create($product_request);
        return $this->response(1, 8000, "product successfully created", $data);
    }

    public function get_all(){
        $resource_options = $this->parse_resource_options();
        $data = $this->product_service->get_all($resource_options);
        return $this->response(1, 8000, "all products", $data);
    }
    public function get_by_id($company_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->product_service->get_by_id($company_id,$resource_options);
        return $this->response(1, 8000, "company details", $data);
    }


}
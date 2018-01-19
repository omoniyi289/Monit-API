<?php
/**
 * Created by PhpStorm.
 * User: e360
 * Date: 1/12/18
 * Time: 5:23 PM
 */

namespace App\Http\Controllers;
use App\Requests\ApiCompanyRequest;
use App\Requests\ApiProductsPriceRequest;
use App\Services\CompanyService;
use App\Services\ProductPriceChangeLogService;
use Core\Controllers\BaseController;

class ProductPriceChangeLogsController extends BaseController
{
    private $product_price_change_logs_service;

    public function __construct(ProductPriceChangeLogService $product_price_change_logs_service)
    {
        $this->product_price_change_logs_service = $product_price_change_logs_service;
    }

    public function create(ApiProductsPriceRequest $request){
        $product_change_log_request = $request->get('product_change_log',[]);
        $data = $this->product_price_change_logs_service->create($product_change_log_request);
        return $this->response(1, 8000, "product changed, but yet to be approved", $data);
    }

    public function get_all(){
        $resource_options = $this->parse_resource_options();
        $data = $this->product_price_change_logs_service->get_all($resource_options);
        return $this->response(1, 8000, "all product price change logs", $data);
    }
    public function get_by_id($product_price_change_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->product_price_change_logs_service->get_by_id($product_price_change_id,$resource_options);
        return $this->response(1, 8000, "company details", $data);
    }

}
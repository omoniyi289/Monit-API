<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/12/18
 * Time: 5:23 PM
 */

namespace App\Http\Controllers;
use App\Requests\ApiCompanyRequest;
use App\Requests\ApiProductsPriceRequest;
use App\Services\CompanyService;
use App\Services\ProductPriceService;
use Core\Controllers\BaseController;

class ProductPriceController extends BaseController
{
    private $product_price_service;

    public function __construct(ProductPriceService $product_price_service)
    {
        $this->product_price_service = $product_price_service;
    }

   /* public function create(ApiProductsPriceRequest $request){
        $product_change_log_request = $request->get('product_change_log',[]);
        $data = $this->product_price_service->create($product_change_log_request);
        //if($data == 'ERROR 400'){
        //   return $this->response(0, 8010, null, null, 400);
        //}
        return $this->response(1, 8000, "product changed, but yet to be approved", $data);
    }
*/
    public function get_all(){
        $resource_options = $this->parse_resource_options();
        $data = $this->product_price_service->get_all($resource_options);
        return $this->response(1, 8000, "all product price change logs", $data);
    }
    public function get_by_id($product_price_change_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->product_price_service->get_by_id($product_price_change_id,$resource_options);
        return $this->response(1, 8000, "price details", $data);
    }
    public function get_by_station_id($station_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->product_price_service->get_by_station_id($station_id,$resource_options);
        return $this->response(1, 8000, "product price details", $data);
    }

}
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
use App\Services\ProductPriceChangeLogService;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;
use App\User;
use App\Station;
use App\Products;
use App\ProductPrices;
use App\ProductChangeLogs;
use App\Services\SoapPriceChangeService;

class ProductPriceChangeLogsController extends BaseController
{
    private $product_price_change_logs_service;

    public function __construct(ProductPriceChangeLogService $product_price_change_logs_service)
    {
        $this->product_price_change_logs_service = $product_price_change_logs_service;
    }

    public function create_default(ApiProductsPriceRequest $request){
        $product_change_log_request = $request->get('product_change_log',[]);
        $data = $this->product_price_change_logs_service->create_default($product_change_log_request);
         if($data == 'ERROR 400'){
           return $this->response(0, 8013, null,null, 400);
        }
        return $this->response(1, 8000, "product changed, but yet to be approved", $data);
    }
    public function create_new_request(ApiProductsPriceRequest $request){
        $product_change_log_request = $request->get('product_change_log',[]);
        $data = $this->product_price_change_logs_service->create_new_request($product_change_log_request);
        return $this->response(1, 8000, "product changed, but yet to be approved", $data);
    }

    public function execute_approval(ApiProductsPriceRequest $request){
         $data = $request->get('product_change_log', []);
        $output = '';
       // return $data['set_time'];
        $request = ProductChangeLogs::where('id', $data['log_id'])->update(['executed_by' => $data['executed_by'], 'is_executed' =>$data['is_executed'], 'valid_from' =>$data['set_time']]);
        
        
        if($data['is_executed'] == 1){
            $prd = ProductChangeLogs::where('id', $data['log_id'])->get()->first();
           $reset = ProductPrices::where('product_id', $prd['product_id'])->update(['new_price_tag' => $prd['requested_price_tag']]);
           $output = [ "code" => 1, "description"=>"price changed successfully"];//SBE Station Process ends here
           //change for automated station
           $station = Station::where('id', $prd['station_id'])->get()->first();
           if(isset($station->oem_stationid) and $station->oem_stationid != NULL){
            $product = Products::where('id', $prd['product_id'])->get()->first();
            //return $product;
             $params = [
                 "stationid"      => $station['oem_stationid'],
                 "productcode"      => $product['code'],
                 "validfrom"      => date_format(date_create($data['set_time']),"Y-m-d") . 'T' . date_format(date_create($data['set_time']),"H:i:s"),
                 "newprice"      => $prd['requested_price_tag']

             ];  
                     // return $params;
                      $output = SoapPriceChangeService::change_price($params);
                      if($output['code'] == 1){
                        $reset = ProductPrices::where('product_id', $prd['product_id'])->where('station_id', $prd['station_id'])->update(['new_price_tag' => $prd['requested_price_tag']]);
                      }
           }
           else{
            ///SBE stations
            $reset = ProductPrices::where('product_id', $prd['product_id'])->where('station_id', $prd['station_id'])->update(['new_price_tag' => $prd['requested_price_tag']]);
           }
            }

         return $this->response(1, 8000, "request successfully updated", $output);
        //return $this->response(1, 8000, "product price changed", $data);
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
    public function get_by_station_id($station_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->product_price_change_logs_service->get_by_station_id($station_id,$resource_options);
        return $this->response(1, 8000, "product price details", $data);
    }

}
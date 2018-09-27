<?php
namespace App\Http\Controllers;

use App\Requests\ApiItemVariantRequest;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Services\ItemVariantService;
class ItemVariantController extends BaseController
{
    private $item_service;

    public function __construct(ItemVariantService $item_service)
    {
        $this->item_service = $item_service;
    }

    public function create(ApiItemVariantRequest $request) {

        $item_request = $request->get('item_variant',[]);

        $data = $this->item_service->get_by_compositesku($item_request);

        if( count($data) > 0 ){
        return $this->response(0, 8000, "Oops! Composite SKU already exist", null, 400);
        }

        $data = $this->item_service->create($item_request);
        return $this->response(1, 8000, "variant successfully created", $data);
    }
    public function stock_refill(Request $request) {

        $item_request = $request->all();
        $data = $this->item_service->stock_refill($item_request);
        return $this->response(1, 8000, "variant successfully refilled", $data);
    }
    public function stock_count(Request $request) {

        $item_request = $request->all();
        $data = $this->item_service->stock_count($item_request);
        return $this->response(1, 8000, "variant successfully counted", $data);
    }
    public function stock_sales(Request $request) {

        $item_request = $request->all();
        $data = $this->item_service->stock_sales($item_request);
        return $this->response(1, 8000, "sales successfully stored", $data);
    }
    public function post_stock_transfer(Request $request) {
        $item_request = $request->all();
        $data = $this->item_service->post_stock_transfer($item_request);
        return $this->response(1, 8000, "variant successfully transfered", $data);
    }
    public function patch_stock_transfer(Request $request) {
        $item_request = $request->all();
        $data = $this->item_service->patch_stock_transfer($item_request);
        return $this->response(1, 8000, "variant successfully transfered", $data);
    }
    public function get_stock_transfer($station_id) {
        //$item_request = $request->all();
        $data = $this->item_service->get_stock_transfer($station_id);
        return $this->response(1, 8000, "transfers", $data);
    }
      public function update(Request $request)
    {   
        $item_request = $request->get('item_variant', []);
         $data = $this->item_service->get_by_compositesku($item_request);

        if( count($data) > 0 and $data['id'] != $item_request['id']){
        return $this->response(0, 8000, "Oops! Composite SKU already exist", null, 400);
        }
        $data = $this->item_service->update($item_request);
        return $this->response(1, 8000, "variant successfully updated", $data);
    }

    public function get_all(){
        $resource_options = $this->parse_resource_options();
        $data = $this->item_service->get_all($resource_options);
        return $this->response(1, 8000, "variants", $data);
    }

    public function get_by_item_id($item_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->item_service->get_by_item_id($item_id,$resource_options);
        return $this->response(1, 8000, "requested variants", $data);
    }

    
    public function get_by_params(Request $request) {
        $item_request = $request->all();
        //$resource_options = $this->parse_resource_options();
        $data = $this->item_service->get_by_params($item_request);
        return $this->response(1, 8000, "requested variants", $data);
    }
     public function delete($item_id) {
            try {
                $resource_options = $this->parse_resource_options();
                $data = $this->item_service->delete($item_id, $resource_options);
                return $this->response(1, 8000, "item deleted", $data);
            }catch (Exception $exception){
                return $this->response(0, 8000, $exception->getMessage(), null,500);
            }
        }

}
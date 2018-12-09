<?php
namespace App\Http\Controllers;

use App\Requests\ApiItemRequest;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Services\ItemService;
class ItemController extends BaseController
{
    private $item_service;

    public function __construct(ItemService $item_service)
    {
        $this->item_service = $item_service;
    }

    public function create(ApiItemRequest $request) {

        $item_request = $request->get('item',[]);
        $data = $this->item_service->get_by_parentsku($item_request);

        if( count($data) > 0 ){
        return $this->response(0, 8000, "Oops! Parent SKU already exist", null, 400);
        }
        $data = $this->item_service->create($item_request);
        return $this->response(1, 8000, "item successfully created", $data);
    }
      public function update(Request $request)
    {
        $item_request = $request->get('item', []);
        $data = $this->item_service->get_by_parentsku($item_request);
        
        if( count($data) > 0 and $data['id'] != $item_request['id']){
        return $this->response(0, 8000, "Oops! Parent SKU already exist", null, 400);
        }

        $data = $this->item_service->update($item_request);
        return $this->response(1, 8000, "item successfully updated", $data);
    }

    public function get_all(){
        $resource_options = $this->parse_resource_options();
        $data = $this->item_service->get_all($resource_options);
        return $this->response(1, 8000, "items", $data);
    }

    public function get_by_company_id($company_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->item_service->get_by_company_id($company_id,$resource_options);
        return $this->response(1, 8000, "requested items", $data);
    }
    
     public function get_by_params(Request $request) {
        //return $request;
        $item_request = $request->all();
        $resource_options = $this->parse_resource_options();
        $data = $this->item_service->get_by_params($item_request);
        return $this->response(1, 8000, "requested items", $data);
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
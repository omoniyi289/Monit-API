<?php
namespace App\Http\Controllers;

use App\Requests\ApiItemRequest;
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

        $item_request = $request->get('item',[]);
        $data = $this->item_service->create($item_request);
        return $this->response(1, 8000, "variant successfully created", $data);
    }
      public function update(Request $request)
    {
        $item_request = $request->get('item', []);
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
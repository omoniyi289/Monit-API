<?php
namespace App\Http\Controllers;

use App\Requests\ApiExpensesRequest;
use App\Services\ExpensesService;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;

class ExpensesController extends BaseController
{
    private $expenses_service;

    public function __construct(ExpensesService $expenses_service)
    {
        $this->expenses_service = $expenses_service;
    }

    public function create(ApiExpensesRequest $request) {

        $expense_update_request = $request->get('expenses',[]);
        $data = $this->expenses_service->create($expense_update_request);
        return $this->response(1, 8000, "expenses successfully created", $data);
    }
      public function update(Request $request)
    {
        $expense_update_request = $request->get('expenses', []);
        $data = $this->expenses_service->update($expense_update_request);
        if($data == 'invalid code'){        
            return $this->response(0, 8000, "invalid code supplied", null, 400);
            }else{
            return $this->response(1, 8000, "request successfully updated", $data);     
            }
    }

    public function get_all(){
        $resource_options = $this->parse_resource_options();
        $data = $this->expenses_service->get_all($resource_options);
        return $this->response(1, 8000, "expenses requests", $data);
    }

    public function get_by_id($stock_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->expenses_service->get_by_id($stock_id,$resource_options);
        return $this->response(1, 8000, "request details", $data);
    }
   
    public function get_by_station_id($station_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->expenses_service->get_by_station_id($station_id,$resource_options);
        return $this->response(1, 8000, "requested expenses", $data);
    }


}
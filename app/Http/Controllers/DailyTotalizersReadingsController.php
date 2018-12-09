<?php
namespace App\Http\Controllers;

use App\Requests\ApiCompanyRequest;
use App\Requests\ApiDailyTotalizersReadingsRequest;
use App\Services\CompanyService;
use App\Services\DailyTotalizersReadingsService;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;
use Response;

class DailyTotalizersReadingsController extends BaseController
{
    private $daily_totalizers_readings_service;
    
    public function __construct(DailyTotalizersReadingsService $daily_totalizers_readings_service)
    {
        $this->daily_totalizers_readings_service = $daily_totalizers_readings_service;
    }

    public function create(ApiDailyTotalizersReadingsRequest $request) {

        $totalizer_request = $request->get('pumps',[]);
        $data = $this->daily_totalizers_readings_service->create($totalizer_request);
        if($data == 'invalid_input'){        
            return $this->response(0, 8000, "no sales reading supplied", null, 400);
            }else{
        return $this->response(1, 8000, "sales successfully created", $data);
        }
    }
      public function update(Request $request)
    {
        $totalizer_update_request = $request->get('pumps', []);
        $data = $this->daily_totalizers_readings_service->update($totalizer_update_request);
        return $this->response(1, 8000, "sales successfully updated", $data);
    }
    
     public function get_template_csv(Request $request){
         //    $headers = array('Content-Type: application/pdf');
       //     return  response()->download(public_path('/stock.csv'), 'info.pdf', $headers);
    }
     public function delete_by_params(Request $request)
    {
        $totalizer_update_request = $request->all();
        $data = $this->daily_totalizers_readings_service->delete_by_params($totalizer_update_request);
        return $this->response(1, 8000, "sales deleted", $data);
    }
       public function file_upload(Request $request)
    {
        $totalizer_update_request = $request;
        $data = $this->daily_totalizers_readings_service->handle_file_upload($totalizer_update_request);
        return $this->response(1, 8000, "sales file loaded", $data);
    }
       public function parsed_csv_data(Request $request)
    {
        $totalizer_update_request = $request->get('pumps', []);
        $data = $this->daily_totalizers_readings_service->upload_parsed_csv_data($totalizer_update_request);
        return $this->response(1, 8000, "sales successfully uploaded", $data);
    }


    public function get_all(){
        $resource_options = $this->parse_resource_options();
        $data = $this->daily_totalizers_readings_service->get_all($resource_options);
        return $this->response(1, 8000, "totalizers", $data);
    }

    public function get_by_id($stock_id) {
        $resource_options = $this->parse_resource_options();
        $data = $this->daily_totalizers_readings_service->get_by_id($stock_id,$resource_options);
        return $this->response(1, 8000, "totalizers details", $data);
    }
   
    public function get_by_params(Request $request) {
        $stock_update_request = $request->all();
        $resource_options = $this->parse_resource_options();
        $data = $this->daily_totalizers_readings_service->get_by_params($stock_update_request);
        return $this->response(1, 8000, "requestedd totalizers", $data);
    }

}
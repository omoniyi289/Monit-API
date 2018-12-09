<?php
namespace App\Http\Controllers;

use App\Requests\ApiItemCustomUploadRequest;
use App\Services\ItemCustomUploadService;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;

class ItemCustomUploadController extends BaseController
{
    private $deposits_service;

    public function __construct(ItemCustomUploadService $deposits_service)
    {
        $this->deposits_service = $deposits_service;
    }



          public function file_upload(Request $request)
    {
        $deposit_request = $request;
        $data = $this->deposits_service->handle_file_upload($deposit_request);
        return $this->response(1, 8000, "stock file loaded", $data);
    }
   
       public function parsed_csv_data(Request $request)
    {
        $deposit_request = $request->get('deposits', []);
        $data = $this->deposits_service->upload_parsed_csv_data($deposit_request);
        return $this->response(1, 8000, "stock successfully uploaded", $data);
    }


}
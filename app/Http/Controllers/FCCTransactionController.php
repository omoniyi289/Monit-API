<?php
namespace App\Http\Controllers;

use App\Services\FCCTransactionService;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;

class FCCTransactionController extends BaseController
{
    private $fcc_transaction_service;

    public function __construct(FCCTransactionService $fcc_transaction_service)
    {
        $this->fcc_transaction_service = $fcc_transaction_service;
    }


    public function get_by_params(Request $request) {
        $data = $request->all();
        $data = $this->fcc_transaction_service->get_requested_transaction_by_seq_no($data);
        return $this->response(1, 8000, "request details", $data);
    }
   
  


}
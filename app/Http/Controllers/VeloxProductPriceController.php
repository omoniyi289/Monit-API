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
use App\Services\VeloxProductPriceService;
use Illuminate\Http\Request;
use Core\Controllers\BaseController;

class VeloxProductPriceController extends BaseController
{
    private $product_price_service;

    public function __construct(VeloxProductPriceService $product_price_service)
    {
        $this->product_price_service = $product_price_service;
    }

    public function get_by_params(Request $request) {
        $data = $this->product_price_service->get_by_params($request->all());
        return $this->response(1, 8000, "requested pumps", $data);
    }

}
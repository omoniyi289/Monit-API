<?php
namespace App\Http\Controllers;

use Core\Controllers\BaseController;
use Illuminate\Http\Request;
use App\User;
use App\Station;
use App\Products;
use App\ProductPrices;
use App\ProductChangeLogs;
class FromMail_PriceChangeController extends BaseController
{
   

      public function update(Request $request)
    {
        $data = $request->get('details', []);
        $req = ProductChangeLogs::where('id', $data['log_id'])->update(['updated_by' => $data['updated_by'], 'is_approved' =>$data['is_approved']]);
        
        
        if($data['is_approved'] == 1){
            $prd = ProductChangeLogs::where('id', $data['log_id'])->get()->first();
           $prd = ProductPrices::where('product_id', $prd['product_id'])->update(['new_price_tag' => $prd['requested_price_tag']]);
           $req = 1;
            }

         return $this->response(1, 8000, "request successfully updated", $req);
    }


}
<?php
namespace App\Http\Controllers;

use Core\Controllers\BaseController;
use Illuminate\Http\Request;
use App\User;
use App\Station;
use App\Mail\PriceChangeExecuteMail;
use Mail;
use App\Products;
use App\ProductPrices;
use App\ProductChangeLogs;
use App\Services\SoapPriceChangeService;

class FromMail_PriceChangeController extends BaseController
{
   

      public function update(Request $request)
    {
        $data = $request->get('details', []);
       //$data = $request->all();
      //  return $data;
        //$data['set_time'] = "2017-06-12 06:06:06";
        $output = ProductChangeLogs::where('id', $data['log_id'])->update(['approved_by' => $data['approved_by'], 'is_approved' =>$data['is_approved']]);
        $approver = User::where('id', $data['approved_by'])->get()->first();
        
        if($data['is_approved'] == 1){
            $prd = ProductChangeLogs::where('id', $data['log_id'])->get()->first();
           $station = Station::with('station_users.user.role.role_permissions.permission')->where('id', $prd['station_id'])->get()->first();
           //send mail to executors for this stastion with EPCR permision
                   $product= Products::where('id', $prd['product_id'])->first();
                    $prd['product_name'] = $product['name'];

                    $station_users =  $station->station_users;
                    foreach ($station_users as $key => $value) {
                        $user =  $value->user;
                        $role_permissions = $user->role->role_permissions;
                        foreach ($role_permissions as $key => $value) {
                                    $permission = $value->permission;
                                if($permission['UI_slug'] == "EPCR"){
                                        Mail::to($user['email'])->send(new PriceChangeExecuteMail($station,$user,$approver['fullname'], $prd ));
                                    }
                                
                            }    
                      }
            
            }

         return $this->response(1, 8000, "request successfully updated", $output);
    }
   
}
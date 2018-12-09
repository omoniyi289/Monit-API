<?php
namespace App\Http\Controllers;

use App\Models\FuelSupply;
use App\Mail\SupplyAcknowledgementMail;
use Mail;
use App\Services\FuelSupplyService;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;
use App\User;
use App\Station;
use App\Products;
use App\RolePermission;
use App\Permission;

class FromMail_FuelSupplyController extends BaseController
{
    private $fuel_supply_service;

    public function __construct(FuelSupplyService $fuel_supply_service)
    {
        $this->fuel_supply_service = $fuel_supply_service;
    }

      public function update(Request $request)
    {    
        $reply_id = 0;
        $data = $request->get('details', []);
        $req = FuelSupply::where('request_code', $data['request_code'])->update(['last_modified_by' => $data['last_modified_by'], 'status' =>$data['status']]);
        if($req==0){
         //return $this->response(1, 8000, "request successfully updated", $reply_id);
         return $this->response(0, 8000, "invalid request code",null,400);
        }else{
            $reply_id = 1;
        }
        
        if($data['status'] == 'Approved'){
            $req = FuelSupply::where('request_code', $data['request_code'])->get()->first();
           $station = Station::with('station_users.user.role.role_permissions.permission')->where('id', $req['station_id'])->get()->first();
           //send mail to executors for this station with PFRe permision
                   $product= Products::where('id', $req['product_id'])->first();
                    $req['product_name'] = $product['name'];
                    if(count($station) > 0 and $station->station_users !== null ){
                    $station_users =  $station->station_users;
                    foreach ($station_users as $key => $value) {
                        $user =  $value->user;
                        if($user->role !== null ){
                        $role_permissions = $user->role->role_permissions;
                        foreach ($role_permissions as $key => $value) {
                                    $permission = $value->permission;
                                if($permission['UI_slug'] == "PFRe"){
                                           Mail::to($user['email'])->send(new SupplyAcknowledgementMail($station,$user, $req , $req['request_code']));

                                    }
                                }
                            }    
                      }
                    
            
           $reply_id = 1;
            }
            }

         return $this->response(1, 8000, "request successfully updated", $reply_id);
    }


}
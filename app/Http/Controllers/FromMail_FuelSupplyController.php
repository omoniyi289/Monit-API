<?php
namespace App\Http\Controllers;

use App\Models\FuelSupply;
use App\Mail\SupplyAcknowledgementMail;
use Mail;
use App\Services\FuelSupplyService;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\CompanyUsers;
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
            $station = Station::where('id', $req['station_id'])->get()->first();
            ///change this to priviledged guys later
            $permission = Permission::where('UI_slug', 'PFRe')->get()->first();
            $roles= RolePermission::where('permission_id', $permission['id'])->get();
            if(count($roles) >0 ){
            $user = CompanyUsers::with('role');
            foreach ($roles as $key => $value) {
                if($key == 0){
                    $user= $user->where('role_id', $value['role_id']);
                }else{
                    $user= $user->orWhere('role_id', $value['role_id']);
                     }
             }
             $user= $user->get();
        //return $data['last_modified_by'];
            $sss= Products::where('id', $req['product_id'])->first();
            $req['product_name'] = $sss['name'];
           // return $req;
            foreach ($user as $value2) {
                Mail::to($value2['email'])->send(new SupplyAcknowledgementMail($station,$value2, $req , $req['request_code']));
            }
            
           $reply_id = 1;
           //return $user;
            }
            }

         return $this->response(1, 8000, "request successfully updated", $reply_id);
    }


}
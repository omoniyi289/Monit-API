<?php
namespace App\Http\Controllers;

use Core\Controllers\BaseController;
use Illuminate\Http\Request;
use App\User;
use App\Station;
use App\Products;
use App\ProductPrices;
use App\ProductChangeLogs;
use App\Services\SoapPriceChangeService;
use App\Events\PriceChangeExecutionGenerated;
use App\Events\PriceChangeApprovalGenerated;

class FromMail_PriceChangeController extends BaseController
{
   

      public function update(Request $request)
    {
        $data = $request->get('details', []);
       //$data = $request->all();
      //  return $data;
        //$data['set_time'] = "2017-06-12 06:06:06";
        $output = 0;
        $approver = User::where('id', $data['approved_by'])->get()->first();
        
        if(isset($data['is_approved_level_3']) and $data['is_approved_level_3'] != null){
             $output = ProductChangeLogs::where('id', $data['log_id'])->update(['approved_by' => $data['approved_by'], 'is_approved_level_3' =>$data['is_approved_level_3']]);
          if($data['is_approved_level_3'] == 1){
            $product_change_result = ProductChangeLogs::where('id', $data['log_id'])->get()->first();
           $station = Station::with('station_users.user.role.role_permissions.permission')->where('id', $product_change_result['station_id'])->get()->first();
           //send mail to executors for this stastion with EPCR permision
                   $product= Products::where('id', $product_change_result['product_id'])->first();
                    $product_change_result['product_name'] = $product['name'];
                    if(count($station) > 0 and $station->station_users !== null ){
                    $station_users =  $station->station_users;
                    foreach ($station_users as $key => $value) {
                        $user =  $value->user;
                        if($user->role !== null ){
                        $role_permissions = $user->role->role_permissions;
                        foreach ($role_permissions as $key => $value) {
                                    $permission = $value->permission;
                                if($permission['UI_slug'] == "EPCR"){
                                    
                                        $mail_data = ['station'=> $station, 'user'=>$user, 'last_updated_by' => $approver['fullname'],'product_change_result' => $product_change_result];
                                        event(new PriceChangeExecutionGenerated($mail_data));
                                    }
                                }
                            }    
                      }
                    }
                  }
            
            }
            else if( isset($data['is_approved_level_2']) and $data['is_approved_level_2'] != null ){
             $output = ProductChangeLogs::where('id', $data['log_id'])->update(['approved_by' => $data['approved_by'], 'is_approved_level_2' =>$data['is_approved_level_2']]);
              if($data['is_approved_level_2'] == 1){
              $product_change_result = ProductChangeLogs::where('id', $data['log_id'])->get()->first();
             $station = Station::with('station_users.user.role.role_permissions.permission')->where('id', $product_change_result['station_id'])->get()->first();
             //send mail to executors for this stastion with EPCR permision
                      $product= Products::where('id', $product_change_result['product_id'])->first();
                      $product_change_result['product_name'] = $product['name'];
                      //set the approval level to level 1
                      $product_change_result['is_approved_type'] = 'is_approved_level_3';
                      $product_change_result['approval_level_indicator_string'] = 'Last Approved By';
                      if(count($station) > 0 and $station->station_users !== null ){
                      $station_users =  $station->station_users;
                      ///first try send to users with level 3 having ensured there is a third level
                      $is_email_sent = false;
                      if( $station['pc_approval_levels'] == 3 ){
                      foreach ($station_users as $key => $value) {
                          $user =  $value->user;
                          if($user->role !== null ){
                          $role_permissions = $user->role->role_permissions;
                          foreach ($role_permissions as $key => $value) {
                                      $permission = $value->permission;
                                  if($permission['UI_slug'] == "APCRL3"){
                                       
                                            $is_email_sent = true;
                                           

                                           $mail_data = ['station'=> $station, 'user'=>$user, 'last_updated_by' => $approver['fullname'],'product_change_result' => $product_change_result];
                                           event(new PriceChangeApprovalGenerated($mail_data));
                                      }
                                  }
                              }    
                        }
                      }
                        ///if no level 3, send to executors (this covers for stations with 2 levels as well)

                        if(!$is_email_sent){
                          foreach ($station_users as $key => $value) {
                          $user =  $value->user;
                          if($user->role !== null ){
                          $role_permissions = $user->role->role_permissions;
                          foreach ($role_permissions as $key => $value) {
                                      $permission = $value->permission;
                                  if($permission['UI_slug'] == "EPCR"){
                              
                                          $mail_data = ['station'=> $station, 'user'=>$user, 'last_updated_by' => $approver['fullname'],'product_change_result' => $product_change_result];
                                          event(new PriceChangeExecutionGenerated($mail_data));
                                      }
                                        }
                                    }    
                              }
                            }
                        }
                      }        
            
            }
             else if( isset($data['is_approved']) and $data['is_approved'] != null ){
              //approval for level 1
             $output = ProductChangeLogs::where('id', $data['log_id'])->update(['approved_by' => $data['approved_by'], 'is_approved' =>$data['is_approved']]);
              if($data['is_approved'] == 1){
              $product_change_result = ProductChangeLogs::where('id', $data['log_id'])->get()->first();
              $station = Station::with('station_users.user.role.role_permissions.permission')->where('id', $product_change_result['station_id'])->get()->first();
             //send mail to approvers for this station with APCRL2 permision
                      $product= Products::where('id', $product_change_result['product_id'])->first();
                      $product_change_result['product_name'] = $product['name'];
                      //set the approval level to level 1
                      $product_change_result['is_approved_type'] = 'is_approved_level_2';
                      $product_change_result['approval_level_indicator_string'] = 'Last Approved By';
                      if(count($station) > 0 and $station->station_users !== null ){
                      $station_users =  $station->station_users;
                      ///first try send to users with level 2 having ensured there is a second or third level
                      $is_email_sent = false;
                      if( $station['pc_approval_levels'] >= 2 ){
                      foreach ($station_users as $key => $value) {
                          $user =  $value->user;
                          if($user->role !== null ){
                          $role_permissions = $user->role->role_permissions;
                          foreach ($role_permissions as $key => $value) {
                                      $permission = $value->permission;
                                  if($permission['UI_slug'] == "APCRL2"){
                                         
                                            $is_email_sent = true;
                                           $mail_data = ['station'=> $station, 'user'=>$user, 'last_updated_by' => $approver['fullname'],'product_change_result' => $product_change_result];
                                           event(new PriceChangeApprovalGenerated($mail_data));
                                      }
                                  }
                              }    
                        }
                      }
                        ///if no level 2, send to level 3 having ensured there is a second or third level
                      if(!$is_email_sent){
                        if( $station['pc_approval_levels'] >= 2 ){
                          foreach ($station_users as $key => $value) {
                          $user =  $value->user;
                          if($user->role !== null ){
                          $role_permissions = $user->role->role_permissions;
                          foreach ($role_permissions as $key => $value) {
                                      $permission = $value->permission;
                                  if($permission['UI_slug'] == "APCRL3"){
                                         
                                            $is_email_sent = true;
                                           $mail_data = ['station'=> $station, 'user'=>$user, 'last_updated_by' => $approver['fullname'],'product_change_result' => $product_change_result];
                                           event(new PriceChangeApprovalGenerated($mail_data));
                                      }
                                        }
                                    }    
                              }
                            }
                          }

                    ///if no level 3, send to executors (this covers for stations with 1 level as well)
                      if(!$is_email_sent){
                          foreach ($station_users as $key => $value) {
                          $user =  $value->user;
                          if($user->role !== null ){
                          $role_permissions = $user->role->role_permissions;
                          foreach ($role_permissions as $key => $value) {
                                      $permission = $value->permission;
                                  if($permission['UI_slug'] == "EPCR"){
                                          $mail_data = ['station'=> $station, 'user'=>$user, 'last_updated_by' => $approver['fullname'],'product_change_result' => $product_change_result];
                                          //return $mail_data;
                                          event(new PriceChangeExecutionGenerated($mail_data));
                                      }
                                        }
                                    }    
                              }
                            }
                      }        
            
            }
          }

         return $this->response(1, 8000, "request successfully updated", $output);
    }
   
}
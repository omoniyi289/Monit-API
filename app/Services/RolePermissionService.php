<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 2:17 AM
 */

namespace App\Services;

use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use App\RolePermission;
use App\Permission;

class RolePermissionService
{
    private $database;

    public function __construct(DatabaseManager $database)
    {
        $this->database = $database;
    }
   
    public function get_all(array $options = []){
        return RolePermission::all();
    }
    public function get_by_id($stock_id, array $options = [])
    {
        return $this->get_requested_stock($stock_id);
    }
   //   public function get_by_station_id($stock_id)
    //{
      // return DailyTotalizerReadings::where('station_id',$stock_id)->get();
    //}
      public function get_by_params($params)
    {   
       $permission = Permission::where('UI_slug', $params['UI_slug'])->get()->first();
       //return $permission;
       $result = RolePermission::where('permission_id',$permission['id'])->where('company_id',$params['company_id'])->with('roles.users_via_permission');

    
       return $result->get();
    }
      public function get_by_request_code($req_code)
    {   

       return RolePermission::where('request_code', $req_code)->with('product')->get();
    
    }
    private function get_requested_stock($stock_id, array $options = [])
    {
        return RolePermission::where('id', $stock_id)->with('product')->get();
    }
}
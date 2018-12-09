<?php
/**
 * Created by PhpStorm.
 * User: omoniyiomotoso
 * Date: 1/14/18
 * Time: 2:17 AM
 */

namespace App\Services;


use App\Reposities\CompanyRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use App\Models\CompanyPermission;
class CompanyPermissionService
{
    private $database;
    public function __construct(DatabaseManager $database)
    {
        $this->database = $database;
    }
   
    public function delete($notification_id, array $options = [])
    {
        return  CompanyPermission::where('id',$notification_id)->delete();
    }

  
    public function get_by_id($user_id, array $options = [])
    {
        return CompanyPermission::where('id', $user_id)->get()->first();
    }
     public function get_by_params($request)
    {
        $result = CompanyPermission::with('permission:id,name');
        if(isset($request['company_id'])){
            $company_id = $request['company_id'];
            $result = $result->where('company_id', $company_id);
        }  
        return $result->get();
    }
  
   
}
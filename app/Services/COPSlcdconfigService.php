<?php
namespace App\Services;

use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use Exception;
use App\Models\COPSlcdconfig;

class COPSlcdconfigService
{
    private $database;
    private $dispatcher;
    private $COPSlcdconfig_repository;

    public function __construct(DatabaseManager $database,Dispatcher $dispatcher)
    {
        $this->database = $database;
        $this->dispatcher = $dispatcher;
    }

    public function create(array $data){
        try {
            $this->database->beginTransaction();
            $COPSlcdconfig = COPSlcdconfig::create($data);  
            }
        catch (\Exception $exception){
            $this->database->rollBack();
            throw  $exception;
        }
        $this->database->commit();
             return $COPSlcdconfig;
    }

   
    public function get_id($COPSlcdconfig_id, array $options = []){
        return $this->get_requested_COPSlcdconfig($COPSlcdconfig_id);
    }

    public function get_COPSlcdconfig_by_name($name, $company_id){
      //  return $this->role_repository->get_where("name",$name);
        return COPSlcdconfig::where("name",$name)->where("company_id",$company_id)->get();
    }
    
   
    public function update($COPSlcdconfig_id,array  $data){
        
        try{
           $updated = COPSlcdconfig::where('id', $COPSlcdconfig_id)->update(['status' => $data['status'], 'name' => $data['name'], 'type' => $data['type'] ]);
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $updated;
        //return COPSlcdconfig::where("id",$COPSlcdconfig['id'])->with('COPSlcdconfig_permissions')->get()->first();
    }
      public function get_by_company_id($company_id)
    {
       return COPSlcdconfig::where("company_id",$company_id)->get();
    }
    public function delete($COPSlcdconfig_id, array $options = [])
    {  
     return  COPSlcdconfig::where('id',$COPSlcdconfig_id)->delete();
    }

    public function get_all($options = []){
        return COPSlcdconfig::get($options);
    }
}
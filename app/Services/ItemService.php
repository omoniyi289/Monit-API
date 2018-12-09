<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/11/18
 * Time: 10:42 AM
 */

namespace App\Services;

use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use Exception;
use App\Models\Items;
use App\Models\ItemVariants;
class ItemService
{
    private $database;
    private $dispatcher;
    private $role_repository;

    public function __construct(DatabaseManager $database,Dispatcher $dispatcher)
    {
        $this->database = $database;
        $this->dispatcher = $dispatcher;
    }

    public function create(array $data){
        try {
            $this->database->beginTransaction();
            try {
                $item = Items::create($data);
                
            } catch (Exception $exception) {
                $this->database->rollBack();
                throw $exception;
            }
            $this->database->commit();
             return $item;
        }catch (\Exception $exception){
            throw  $exception;
        }
    }

    public function get_id($item_id, array $options = []){
        return $this->get_requested_item($item_id);
    }

    private function get_requested_item($item_id, array $options = []){
        return Items::get('id', $item_id)->first();
    }

    public function get_by_parentsku($raw_data){
     return $item = Items::where('parentsku', $raw_data['parentsku'])->where('company_id', $raw_data['company_id'])->get()->first();
    }
    
    public function update(array  $data){
        $this->database->beginTransaction();
        try{
            $item = Items::where('id',$data['id'] )->update(['name'=> $data['name'],
             'category'=>$data['category'], 'brand' => $data['brand'], 'parentsku' => $data['parentsku'], 'uom' => $data['uom']]);
          
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $item;
        //return Role::where("id",$role['id'])->with('role_permissions')->get()->first();
    }
      public function get_by_company_id($company_id)
    {
       return Items::where("company_id",$company_id)->get();
    }
    public function delete($item_id, array $options = [])
    {   
        ItemVariants::where('item_id',$item_id)->delete();
        return  Items::where('id',$item_id)->delete();
    }

   
}
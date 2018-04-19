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
class ItemVariantService
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
                $item = ItemVariants::create($data);
                
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
        return ItemVariants::get('id', $item_id)->first();
    }
    
    public function update(array  $data){
        $this->database->beginTransaction();
        try{
            $item = ItemVariants::where('id',$data['id'] )->update(['name'=> $data['name'],
             'category'=>$data['category'], 'brand' => $data['brand'], 'parentsku' => $data['parentsku'], 'uom' => $data['uom']]);
          
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $item;
        //return Role::where("id",$role['id'])->with('role_permissions')->get()->first();
    }
      public function get_by_item_id($item_id)
    {
       return ItemVariants::where("item_id",$item_id)->get();
    }
    public function delete($item_id, array $options = [])
    {   
       // ItemVariants::where('item_id',$item_id)->delete();
        return  ItemVariants::where('id',$item_id)->delete();
    }

   
}
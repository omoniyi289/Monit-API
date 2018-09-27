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
use App\Models\ItemVariantsByStation;
use App\Models\ItemRestockHistory;
use App\Models\StockCount;
use App\Models\StockSales;
use App\Models\StockTransfer;
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
    public function get_by_compositesku($raw_data){
     return $variant = ItemVariants::where('compositesku', $raw_data['compositesku'])->where('company_id', $raw_data['company_id'])->get()->first();
    }
    public function stock_refill($raw_data){
        try {
            $this->database->beginTransaction();
            try {
                $data =$raw_data['item_variants'];
                $set_return_cred='';
                foreach ($data as $key => $value) {
                      //update history
                   $value['company_id'] = $raw_data['company_id'];
                   $value['station_id'] = $raw_data['station_id'];
                   $set_return_cred = $value;
                   $value['last_restock_date'] = date('Y-m-d H:i:s');
                   $value['restock_id'] = uniqid(6);
                   $value['qty_before_restock']= $value['qty_in_stock'];
                   $value['qty_after_restock']= $value['restock_qty']+$value['qty_in_stock'];
                   

                    ///update if exist else create
                    
                   $variant = ItemVariantsByStation::where('compositesku', $value['compositesku'])->where('station_id', $raw_data['station_id'])->get()->first();
                   if(count($variant) > 0){
                        ////update qty_in_stock with new restockq ty
                        $value['qty_in_stock'] = $variant['qty_in_stock'] + $value['restock_qty'];

                         ItemVariantsByStation::where('compositesku', $value['compositesku'])->where('station_id', $raw_data['station_id'])->update([ 'item_id'=>$value['item_id'], 'company_id' =>$value['company_id'],'station_id'=>$value['station_id'], 'supply_price'=>$value['supply_price'], 'last_restock_date' => $value['last_restock_date'],'compositesku'=>$value['compositesku'], 'retail_price'=>$value['retail_price'],'reorder_level'=>$value['reorder_level'], 'qty_in_stock'=>$value['qty_in_stock'], 'modified_by'=> $raw_data['modified_by']]);
                      //  return array($variant, $value);
                        ///update hjistory if change was made
                        if($variant['supply_price'] != $value['supply_price'] or $variant['retail_price'] != $value['retail_price'] or $variant['qty_in_stock'] != $value['qty_in_stock'] ){
                        ItemRestockHistory::create([ 'item_id'=>$value['item_id'], 'company_id' =>$value['company_id'],'station_id'=>$value['station_id'], 'restock_id'=>$value['restock_id'],  'compositesku'=>$value['compositesku'], 'restock_qty'=>$value['restock_qty'],'qty_before_restock'=>$value['qty_before_restock'], 'qty_after_restock'=>$value['qty_after_restock'], 'created_by'=>$raw_data['created_by'], 'modified_by'=> $raw_data['created_by']]);}

                   }else{
                    ////update qty_in_stock with new restock  qty
                        $value['qty_in_stock'] = $variant['qty_in_stock'] + $value['restock_qty'];
                    ItemVariantsByStation::create($value);
                    //update history
                    ItemRestockHistory::create([ 'item_id'=>$value['item_id'], 'company_id' =>$value['company_id'],'station_id'=>$value['station_id'], 'restock_id'=>$value['restock_id'],  'compositesku'=>$value['compositesku'], 'restock_qty'=>$value['restock_qty'],'qty_before_restock'=>$value['qty_before_restock'], 'qty_after_restock'=>$value['qty_after_restock'], 'created_by'=>$raw_data['created_by'], 'modified_by'=> $raw_data['created_by']]);
                   }
                  

                   
                }
                //$item = ItemVariantsByStation::create($data);
                
            } catch (Exception $exception) {
                $this->database->rollBack();
                throw $exception;
            }
            $this->database->commit();
             $item_varaints = ItemVariants::where("item_id",$set_return_cred['item_id'])->with('item')->get();
             $item_varaints_by_station = ItemVariantsByStation::where("item_id",$set_return_cred['item_id'])->with('item')->where("station_id",$set_return_cred['station_id'])->get();
             return array('item_variants' => $item_varaints ,'item_variants_by_station' => $item_varaints_by_station);
        }catch (\Exception $exception){
            throw  $exception;
        }
    }

public function stock_count($raw_data){
        try {
            $this->database->beginTransaction();
            try {
                $data =$raw_data['item_variants'];
                $set_return_cred='';
                foreach ($data as $key => $value) {
                      //update history
                   $value['company_id'] = $raw_data['company_id'];
                   $value['station_id'] = $raw_data['station_id'];
                   $set_return_cred = $value;
                   $value['last_restock_date'] = date('Y-m-d H:i:s');
                   $value['restock_id'] = uniqid(6);
              
                    ///update if exist else create
                    
                   $variant = ItemVariantsByStation::where('compositesku', $value['compositesku'])->where('station_id', $raw_data['station_id'])->get()->first();
                   if(count($variant) > 0){
                             ////update stock count history if qty_in_stock changed
                  if($value['qty_counted'] != $value['qty_in_stock']){
                   StockCount::create([ 'item_id'=>$value['item_id'], 'company_id' =>$value['company_id'],'station_id'=>$value['station_id'],  'compositesku'=>$value['compositesku'], 'qty_counted'=>$value['qty_counted'], 'qty_in_stock'=>$value['qty_in_stock'], 'created_by'=>$raw_data['created_by']]);
                        }


                        $value['qty_in_stock']= $value['qty_counted'];
                         ItemVariantsByStation::where('compositesku', $value['compositesku'])->where('station_id', $raw_data['station_id'])->update([ 'item_id'=>$value['item_id'], 'company_id' =>$value['company_id'],'station_id'=>$value['station_id'], 'supply_price'=>$value['supply_price'], 'last_restock_date' => $value['last_restock_date'],'compositesku'=>$value['compositesku'], 'qty_in_stock'=>$value['qty_in_stock'], 'modified_by'=> $raw_data['modified_by']]);
                     
                      }

                   else{
                    ////update stock count history if qty_in_stock changed
                  if($value['qty_counted'] != $value['qty_in_stock']){
                   StockCount::create([ 'item_id'=>$value['item_id'], 'company_id' =>$value['company_id'],'station_id'=>$value['station_id'],  'compositesku'=>$value['compositesku'], 'qty_counted'=>$value['qty_counted'], 'qty_in_stock'=>$value['qty_in_stock'], 'created_by'=>$raw_data['created_by']]);
                        }

                    $value['qty_in_stock']= $value['qty_counted'];
                    ItemVariantsByStation::create($value);
                    //update history
                   
                }
            }}
                //$item = ItemVariantsByStation::create($data);
                
             catch (Exception $exception) {
                $this->database->rollBack();
                throw $exception;
            }
            $this->database->commit();
             $item_varaints = ItemVariants::where("item_id",$set_return_cred['item_id'])->with('item')->get();
             $item_varaints_by_station = ItemVariantsByStation::where("item_id",$set_return_cred['item_id'])->where("station_id",$set_return_cred['station_id'])->with('item')->get();
             return array('item_variants' => $item_varaints ,'item_variants_by_station' => $item_varaints_by_station);
        }catch (\Exception $exception){
            throw  $exception;
        }
    }


    public function stock_sales($raw_data){
        try {
            $this->database->beginTransaction();
            try {
                $data =$raw_data['item_variants'];
                $set_return_cred='';
                foreach ($data as $key => $value) {
                      //update history
                   $value['company_id'] = $raw_data['company_id'];
                   $value['station_id'] = $raw_data['station_id'];
                   $set_return_cred = $value;
                
                    ///update if exist else create
                    
                   $variant = ItemVariantsByStation::where('compositesku', $value['compositesku'])->where('station_id', $raw_data['station_id'])->get()->first();
                   if(count($variant) > 0){
                             ////create stock sales if qty_sold > 0 
                  if($value['qty_sold'] > 0){
                   StockSales::create( ['item_id'=>$value['item_id'], 'company_id' =>$value['company_id'],'station_id'=>$value['station_id'],  'compositesku'=>$value['compositesku'], 'qty_sold'=>$value['qty_sold'], 'cash_collected'=>$value['cash_collected'], 'retail_price'=>$value['retail_price'], 'supply_price'=>$value['supply_price'], 'qty_in_stock'=>$value['qty_in_stock'], 'sold_by'=>$raw_data['created_by']] );
                        }

                        //update qty in stock by deducting sales
                        $value['qty_in_stock']= $value['qty_in_stock'] - $value['qty_sold'] ;

                         ItemVariantsByStation::where('compositesku', $value['compositesku'])->where('station_id', $raw_data['station_id'])->update([ 'supply_price' => $value['supply_price'], 'qty_in_stock'=>$value['qty_in_stock'], 'modified_by'=> $raw_data['modified_by']]);
                     
                      }

                   else{
                                ////create stock sales if qty_sold > 0 
                  if($value['qty_sold'] > 0){
                     StockSales::create( ['item_id'=>$value['item_id'], 'company_id' =>$value['company_id'],'station_id'=>$value['station_id'],  'compositesku'=>$value['compositesku'], 'qty_sold'=>$value['qty_sold'], 'cash_collected'=>$value['cash_collected'], 'retail_price'=>$value['retail_price'], 'supply_price'=>$value['supply_price'], 'qty_in_stock'=>$value['qty_in_stock'], 'sold_by'=>$raw_data['created_by']] );

                        }

                    $value['qty_in_stock']= $value['qty_in_stock'] - $value['qty_sold'];
                    ItemVariantsByStation::create($value);
                    //update history
                   
                }
            }}
                //$item = ItemVariantsByStation::create($data);
                
             catch (Exception $exception) {
                $this->database->rollBack();
                throw $exception;
            }
            $this->database->commit();
             $item_varaints = ItemVariants::where("item_id",$set_return_cred['item_id'])->with('item')->get();
             $item_varaints_by_station = ItemVariantsByStation::where("item_id",$set_return_cred['item_id'])->where("station_id",$set_return_cred['station_id'])->with('item')->get();
             return array('item_variants' => $item_varaints ,'item_variants_by_station' => $item_varaints_by_station);
        }catch (\Exception $exception){
            throw  $exception;
        }
    }

public function post_stock_transfer($raw_data){
        try {
            $this->database->beginTransaction();
            try {
                $data =$raw_data['item_variants'];
                $set_return_cred='';
                foreach ($data as $key => $value) {
                      //update history
                   
                   $set_return_cred = $value;
                   $value['date_transfered'] = date('Y-m-d H:i:s');
                   $value['restock_id'] = uniqid(6);
              
                    ///update if exist on receiving station else create
                    
                   $rx_variant = ItemVariantsByStation::where('compositesku', $value['compositesku'])->where('station_id', $raw_data['rx_station_id'])->get()->first();
                   if(count($rx_variant) > 0){
                             ////update stock count history if qty_in_stock changed
                  if($value['quantity_transferred'] != 0){
                   StockTransfer::create([ 'item_id'=>$value['item_id'], 'company_id' =>$value['company_id'],'rx_station_id'=>$raw_data['rx_station_id'],'tx_station_id'=>$raw_data['tx_station_id'],  'compositesku'=>$value['compositesku'], 'quantity_transferred'=>$value['quantity_transferred'], 'date_transfered'=>$value['date_transfered'], 'transfered_by'=>$raw_data['transfered_by'], 'status'=> 'Completed']);
                        }


                    /*    $value['qty_in_stock']= $rx_variant['qty_in_stock'] + $value['quantity_transferred'];
                         ItemVariantsByStation::where('compositesku', $value['compositesku'])->where('station_id', $raw_data['station_id'])->update([ 'item_id'=>$value['item_id'], 'company_id' =>$value['company_id'],'station_id'=>$value['station_id'], 'supply_price'=>$value['supply_price'], 'last_restock_date' => $value['last_restock_date'],'compositesku'=>$value['compositesku'], 'qty_in_stock'=>$value['qty_in_stock'], 'modified_by'=> $raw_data['modified_by']]);*/
                     
                      }

                   else{
                    ////update stock count history if qty_in_stock changed
                  if($value['quantity_transferred'] != 0){
                   StockTransfer::create([ 'item_id'=>$value['item_id'], 'company_id' =>$value['company_id'],'rx_station_id'=>$raw_data['rx_station_id'],'tx_station_id'=>$raw_data['tx_station_id'],  'compositesku'=>$value['compositesku'], 'quantity_transferred'=>$value['quantity_transferred'], 'date_transfered'=>$value['date_transfered'], 'transfered_by'=>$raw_data['transfered_by'], 'status'=> 'Completed']);

                        /*$value['qty_in_stock']= $value['quantity_transferred'];
                        $value['station_id']= $value['rx_station_id'];

                        ItemVariantsByStation::create($value);*/
                        }

                    
                    //update history
                   
                }
            }}
                //$item = ItemVariantsByStation::create($data);
                
             catch (Exception $exception) {
                $this->database->rollBack();
                throw $exception;
            }
            $this->database->commit();
             $item_varaints = ItemVariants::where("item_id",$set_return_cred['item_id'])->with('item')->get();
             $item_varaints_by_station = ItemVariantsByStation::where("item_id",$set_return_cred['item_id'])->where("station_id",$set_return_cred['station_id'])->with('item')->get();
             return array('item_variants' => $item_varaints ,'item_variants_by_station' => $item_varaints_by_station);
        }catch (\Exception $exception){
            throw  $exception;
        }
    }

    public function patch_stock_transfer($raw_data){
        //return $raw_data;
        try {
            $this->database->beginTransaction();
            try {
                $value =$raw_data['item_variant'];
                $set_return_cred='';
                 
                   $set_return_cred = $value;
                   $value['date_received'] = date('Y-m-d H:i:s');

                   $tx_variant = ItemVariantsByStation::where('compositesku', $value['compositesku'])->where('station_id', $value['tx_station_id'])->get()->first();

                   if($value['status'] == 'Received'){
                     StockTransfer::where('id', $value['id'])->update(['quantity_received'=>$value['quantity_received'], 'date_received'=>$value['date_received'], 'received_by'=>$value['received_by'], 'status'=> 'Received']);
                    ///update if exist on receiving station else create
                    
                   $rx_variant = ItemVariantsByStation::where('compositesku', $value['compositesku'])->where('station_id', $value['rx_station_id'])->get()->first();
                   if(count($rx_variant) > 0){
                           
                    ///station receiving, am using quantity_received instead of quantity transferred for ... reasons
                    $rx_variant['qty_in_stock'] = $rx_variant['qty_in_stock'] + $value['quantity_received'];

                    $rx = ItemVariantsByStation::where('compositesku', $value['compositesku'])->where('station_id', $value['rx_station_id'])->update(['qty_in_stock' => $rx_variant['qty_in_stock'] ]);
                    //station transrering 
                    $tx_variant['qty_in_stock'] = $tx_variant['qty_in_stock'] - $value['quantity_received'];

                    $tx = ItemVariantsByStation::where('compositesku', $value['compositesku'])->where('station_id', $value['tx_station_id'])->update(['qty_in_stock'=> $tx_variant['qty_in_stock'] ]);
                     
                      }

                   else{
                         ///station transfering
                        $qty_in_stock = $tx_variant['qty_in_stock'] - $value['quantity_received'];

                        $tx = ItemVariantsByStation::where('compositesku', $value['compositesku'])->where('station_id', $value['tx_station_id'])->update(['qty_in_stock'=> $qty_in_stock]);

                    ///station receiving never had it before
                        //change the parameter for the station receiving
                        $tx_variant['qty_in_stock']= $value['quantity_received'];
                        $tx_variant['station_id']= $value['rx_station_id'];
                        $array = json_decode(json_encode($tx_variant), true);
                        //return $array;
                        ItemVariantsByStation::create($array);

                       

                        }
       
                }else if($value['status']=='Discarded'){
                    //just update the status to what was sent
                    StockTransfer::where('id', $value['id'])->update(['date_received'=>$value['date_received'], 'received_by'=>$value['received_by'], 'status'=> $value['status']]);
                }
            
        }
                //$item = ItemVariantsByStation::create($data);
                
             catch (Exception $exception) {
                $this->database->rollBack();
                throw $exception;
            }
            $this->database->commit();
             return $transfers = StockTransfer::where("rx_station_id",$value['rx_station_id'])->with('item')->with('item_variant')->get();
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
            $item = ItemVariants::where('id',$data['id'] )->update(['variant_option'=> $data['variant_option'],
             'variant_value'=>$data['variant_value'], 'qty_in_stock' => $data['qty_in_stock'], 'compositesku' => $data['compositesku'], 'reorder_level' => $data['reorder_level'], 'last_restock_date' => $data['last_restock_date'], 'retail_price' => $data['retail_price'], 'supply_price' => $data['supply_price']]);
          
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
        public function get_by_params($data)
    {
     //   return $data;
       $item_varaints = ItemVariants::where("item_id",$data['item_id'])->with('item')->get();
       $item_varaints_by_station = ItemVariantsByStation::where("item_id",$data['item_id'])->where("station_id",$data['station_id'])->with('item')->get();
       return array('item_variants' => $item_varaints ,'item_variants_by_station' => $item_varaints_by_station);
    }
    public function get_stock_transfer($station_id)
    {
       // return $data;
      return $transfers = StockTransfer::where("rx_station_id",$station_id)->with('item')->with('item_variant')->get();
    }
    public function delete($item_id, array $options = [])
    {   
       // ItemVariants::where('item_id',$item_id)->delete();
        return  ItemVariants::where('id',$item_id)->delete();
    }

   
}
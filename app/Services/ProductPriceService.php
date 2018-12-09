<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 2:17 AM
 */

namespace App\Services;


use App\Reposities\CompanyRepository;
use App\Reposities\ProductPriceRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use App\ProductPrices;
use App\ProductChangeLogs;


class ProductPriceService
{
    private $database;
    private $dispatcher;
    private $product_price_repository;

    public function __construct(DatabaseManager $database,Dispatcher $dispatcher,
                                ProductPriceRepository $product_price_repository)
    {
        $this->database = $database;
        $this->dispatcher = $dispatcher;
        $this->product_price_repository = $product_price_repository;
    }

    /*public function create(array $data){
        $this->database->beginTransaction();
        try{
            if($data['mode']= 'create'){
            $exist = $this->get_by_station_and_product_id($data['station_id'], $data['product_id']);
            if (count($exist) > 0) {
                return 'ERROR 400';
            }else{   
             $data['new_price_tag'] = $data['requested_price_tag'];      
             $product_price = ProductPrices::create($data);
                }
            }
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $company;
    }*/

    public function get_company_by_name($name){
        return $this->product_price_repository->get_where('name',$name);
    }

    public function get_all(array $options = []){
        return $this->product_price_repository->get($options);
    }
    public function get_by_id($user_id, array $options = [])
    {
        return $this->get_requested_product_price_change($user_id);
    }
    public function get_by_station_id($station_ids, array $options = [])
    {   
        $station_ids = explode(",", $station_ids);
        return ProductPrices::whereIn("station_id", $station_ids)->with('product:id,code')->with('station:id,name,company_id')->get();
    }
    public static function log_price_change($data, array $options = [])
    {
        return ProductChangeLogs::create();
    }
    public function get_by_product_id($station_id, array $options = [])
    {
        return $this->product_price_repository->get_where("product_id", $station_id);
    }
    private function get_requested_product_price_change($user_id, array $options = [])
    {
        return $this->product_price_repository->get_by_id($user_id, $options);
    }
}
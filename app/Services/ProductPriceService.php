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

    public function create(array $data){
        $this->database->beginTransaction();
        try{
            $exist = $this->get_by_product_id($data['product_id']);
            if (count($exist) > 0) {
                $company = $this->product_price_repository->update($data);
            }else{
                $data['current_price_tag'] = $data['requested_price_tag'];
            $company = $this->product_price_repository->create($data);
                }
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $company;
    }

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
    public function get_by_station_id($station_id, array $options = [])
    {
        return ProductPrices::where("station_id", $station_id)->with('product')->get();
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
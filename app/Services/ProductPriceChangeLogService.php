<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 2:17 AM
 */

namespace App\Services;


use App\Reposities\CompanyRepository;
use App\Reposities\ProductPricesLogsRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;

class ProductPriceChangeLogService
{
    private $database;
    private $dispatcher;
    private $product_price_change_log_repository;

    public function __construct(DatabaseManager $database,Dispatcher $dispatcher,
                                ProductPricesLogsRepository $product_price_change_log_repository)
    {
        $this->database = $database;
        $this->dispatcher = $dispatcher;
        $this->product_price_change_log_repository = $product_price_change_log_repository;
    }

    public function create(array $data){
        $this->database->beginTransaction();
        try{
            $company = $this->product_price_change_log_repository->create($data);
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $company;
    }

    public function get_company_by_name($name){
        return $this->product_price_change_log_repository->get_where('name',$name);
    }

    public function get_all(array $options = []){
        return $this->product_price_change_log_repository->get($options);
    }
    public function get_by_id($user_id, array $options = [])
    {
        return $this->get_requested_product_price_change($user_id);
    }
    private function get_requested_product_price_change($user_id, array $options = [])
    {
        return $this->product_price_change_log_repository->get_by_id($user_id, $options);
    }
}
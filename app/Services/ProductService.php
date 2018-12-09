<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 2:17 AM
 */

namespace App\Services;


use App\Reposities\CompanyRepository;
use App\Reposities\ProductsRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;

class ProductService
{
    private $database;
    private $product_repository;

    public function __construct(DatabaseManager $database,ProductsRepository $product_repository)
    {
        $this->database = $database;
        $this->product_repository = $product_repository;
    }

    public function create(array $data){
        $this->database->beginTransaction();
        try{
            $products = $this->product_repository->create($data);
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $products;
    }

    public function get_product_by_name($name){
        return $this->product_repository->get_where('name',$name);
    }

    public function get_all(array $options = []){
        return $this->product_repository->get($options);
    }
    public function get_by_id($user_id, array $options = [])
    {
        return $this->get_requested_product($user_id);
    }
    private function get_requested_product($user_id, array $options = [])
    {
        return $this->product_repository->get_by_id($user_id, $options);
    }
}
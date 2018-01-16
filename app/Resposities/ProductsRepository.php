<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/12/18
 * Time: 5:24 PM
 */

namespace App\Resposities;

use App\Company;
use App\Products;
use Core\Repository\BaseRepository;

class ProductsRepository extends BaseRepository
{

    public function get_model()
    {
        return new Products();
    }
    public function create(array $data){
        $products = $this->get_model();
        $products->fill($data);
        $products->save();
        return $products;
    }


}
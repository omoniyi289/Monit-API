<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 2:04 AM
 */

namespace App\Requests;


use Core\Requests\APIRequest;

class ApiItemVariantRequest extends APIRequest
{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'item_variant' => 'array|required',
           
            'item_variant.qty_in_stock' => 'required|integer',
            //'company.country' => 'required|string',
            'item_variant.retail_price' => 'required|string',
            'item_variant.supply_price' => 'required|string',
            'item_variant.reorder_level' => 'required|integer',
            'item_variant.variant_option' => 'required|string',
            'item_variant.item_id' => 'required|integer',
            'item_variant.last_restock_date' => 'required|string',
            'item_variant.compositesku' => 'required|string',
        ];
    }
    public function attributes()
    {
        return [
            'item.name' => 'the item\'s name'
        ];
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/12/18
 * Time: 5:23 PM
 */

namespace App\Http\Controllers;
use App\Requests\ApiCompanyRequest;
use App\Requests\ApiProductsPriceRequest;
use App\Services\CompanyService;
use App\Services\ProductPriceService;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;

class PagaConnectController extends BaseController
{

    public function connect(Request $request){
      $url = "https://qa1.mypaga.com/paga-webservices/merchant-rest/secured/getTransactionDetails";
      $postdata = "{\"referenceNumber\":\"86122495\"}";
      //$postdata= "{\"baseCurrency\":\"242355\", \"foreignCurrency\":\"242355\"}";
      //hash with sha-512 1000 times
      //appended param values referenceNumber and merchant api key
    
      //  $hash = hash('sha512', "0012939283"."1cae7a05dbea4ba18d2569d14e52ce8526c3d812a1664f338bb845f545ac1de2aa99cd64507545f7a52f296c2e6445f0ff5463933f984ed0887eeac61da28529");

      $hash = hash('sha512', "86122495"."23678adb4bbc4861ade4bb4cc6ff9a4532b4b6486e2748eb992e91c54112b277e3f36eec7bf04e0a933a29b08d28f9783afa28bccad54d96b37f5ce9618a64bd");
      //    $hash = hash('sha512', "242355"."242355"."23678adb4bbc4861ade4bb4cc6ff9a4532b4b6486e2748eb992e91c54112b277e3f36eec7bf04e0a933a29b08d28f9783afa28bccad54d96b37f5ce9618a64bd");
    
   $ch = curl_init();
   curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_HEADER, false); 
    curl_setopt($ch, CURLOPT_POST, strlen($postdata));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);    
 curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json","principal: FB132091-B233-479B-B077-5FB481BC6B1C","credentials: yH7*9efcTxnaTh2","hash:".$hash
    ));
    $output=curl_exec($ch);
 
    curl_close($ch);
    //return $output;
    //echo $result;
        return $output;
    }

   

}
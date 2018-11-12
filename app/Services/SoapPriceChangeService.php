<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 2:17 AM
 */

namespace App\Services;


use App\Reposities\CompanyRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;
use App\Company;
class SoapPriceChangeService
{
    private $database;
    private $dispatcher;
  
    public function __construct(DatabaseManager $database,Dispatcher $dispatcher)
    {
        $this->database = $database;
        $this->dispatcher = $dispatcher;
    }
  public static function getSoapClient()
   {
       $context = stream_context_create([
           'ssl' => [
               // set some SSL/TLS specific options
               'verify_peer' => false,
               'verify_peer_name' => false,
               'allow_self_signed' => true
           ]
       ]);        $wsdlPath = "http://imc.energy360africa.com:98/backend/e360prime_services.asmx?wsdl"; 
              return new \SoapClient($wsdlPath, [
           'stream_context' => $context,
           'trace' => true
       ]);
   }

public static function change_price($params)
   {
        $response =  SoapPriceChangeService::getSoapClient()->change_price($params);    
        $result = $response->change_priceResult;
        $statusCode = $result->responsecode;         
         return  [ "code" => $result->responsecode, "description"=> $result->resultdesc];      
      //     throw new AnalyticsException("Invalid login credentials");  
  }

  
}
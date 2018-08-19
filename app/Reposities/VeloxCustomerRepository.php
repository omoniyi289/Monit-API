<?php
/**
 * Created by PhpStorm.
 * User: omoniyiomotoso
 * Date: 1/12/18
 * Time: 6:45 PM
 */

namespace App\Reposities;
use Illuminate\Support\Facades\DB;
use App\User;
use Core\Repository\BaseRepository;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class VeloxCustomerRepository 
{    
     
    public function get_by_params($request){
      //return $request;
    $client = new Client(); //GuzzleHttp\Client
    $response = $client->request('GET',env('VELOX_API_URL').'/sm_customer_accounts',[
        'query' => $request ] 
        );
     $result= json_decode($response->getBody()->getContents());
     return $result;
    }

     public function update($request){
      //return $request;
    $client = new Client(); //GuzzleHttp\Client
    $response = $client->request('PATCH',env('VELOX_API_URL').'/sm_customer_accounts/'.$request['velox_customer']['id'],[
        'form_params' => $request ] 
        );
     $result= json_decode($response->getBody()->getContents());
     return $result;
    }

}
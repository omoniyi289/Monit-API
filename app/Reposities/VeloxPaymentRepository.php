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

class VeloxPaymentRepository 
{

public function create(array $data){

   $client = new Client(); //GuzzleHttp\Client
    $response = $client->request('POST',env('VELOX_API_URL').'/sm_manage_payments',[
        'form_params' => $data ] 
        );
     $result= json_decode($response->getBody()->getContents());
     return $result;
    }

public function update(array $data){

   $client = new Client(); //GuzzleHttp\Client
    $response = $client->request('PATCH',env('VELOX_API_URL').'/sm_manage_payments/'.$data['customer_payment']['id'],[
        'form_params' => $data ] 
        );
     $result= json_decode($response->getBody()->getContents());
     return $result;
    }

   public function get_by_params($request){
      //return $request;
    $client = new Client(); //GuzzleHttp\Client
    $response = $client->request('GET',env('VELOX_API_URL').'/sm_manage_payments',[
        'query' => $request ] 
        );
     $result= json_decode($response->getBody()->getContents());
     return $result;
    }
}
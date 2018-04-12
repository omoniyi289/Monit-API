<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 2:17 AM
 */

namespace App\Services;

use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use App\Models\Deposits;
class DepositsService
{
    private $database;
    private $pump_repository;

    public function __construct(DatabaseManager $database)
    {
        $this->database = $database;
    }
    public function create(array $data) {
        $this->database->beginTransaction();
        try{
            //$data['deposit_code'] = strtoupper(uniqid('EC'));
            $deposits = Deposits::create($data);
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
         return Deposits::where('id', $deposits['id'])->with('creator')->with('approver')->get()->first();
    }
     public function update($deposit_id, array $data)
    {
        $deposit = Deposits::where('id',$deposit_id);
        $this->database->beginTransaction();
        try {
            Deposits::where('id', $deposit['id'])->update($data);
        } catch (Exception $exception) {
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return Deposits::where('id', $deposit_id)->with('creator')->with('approver')->get()->first();
    }

    public function get_all(array $options = []){
        return Deposits::all();
    }
    public function get_by_id($user_id, array $options = [])
    {
        return get_requested_deposit($user_id);
    }
      public function get_by_station_id($station_id)
    {
       return Deposits::where('station_id',$station_id)->get();
    }
    private function get_requested_deposit($id, array $options = [])
    {
     return Deposits::where('id', $id)->with('creator')->with('approver')->get()->first();
    }
}